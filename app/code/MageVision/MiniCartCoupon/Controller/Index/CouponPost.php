<?php
/**
 * MageVision Mini Cart Coupon Extension
 *
 * @category     MageVision
 * @package      MageVision_MiniCartCoupon
 * @author       MageVision Team
 * @copyright    Copyright (c) 2018 MageVision (http://www.magevision.com)
 * @license      LICENSE_MV.txt or http://www.magevision.com/license-agreement/
 */
namespace MageVision\MiniCartCoupon\Controller\Index;

use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Checkout\Model\Session;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Data\Form\FormKey\Validator;
use Magento\Checkout\Model\Cart;
use Magento\SalesRule\Model\CouponFactory;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Framework\Json\Helper\Data;
use Magento\Framework\View\Result\LayoutFactory;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\LocalizedException;

class CouponPost extends \Magento\Checkout\Controller\Cart
{
    /**
     * Sales quote repository
     *
     * @var CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * Coupon factory
     *
     * @var CouponFactory
     */
    protected $couponFactory;

    /**
     * @var Data
     */
    protected $jsonHelper;

    /**
     * @var LayoutFactory
     */
    protected $resultLayoutFactory;

    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     * @param Session $checkoutSession
     * @param StoreManagerInterface $storeManager
     * @param Validator $formKeyValidator
     * @param Cart $cart
     * @param CouponFactory $couponFactory
     * @param CartRepositoryInterface $quoteRepository
     * @param Data $jsonHelper
     * @param LayoutFactory $resultLayoutFactory
     * @param Escaper $escaper
     * @codeCoverageIgnore
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig,
        Session $checkoutSession,
        StoreManagerInterface $storeManager,
        Validator $formKeyValidator,
        Cart $cart,
        CouponFactory $couponFactory,
        CartRepositoryInterface $quoteRepository,
        Data $jsonHelper,
        LayoutFactory $resultLayoutFactory,
        Escaper $escaper
    ) {
        parent::__construct(
            $context,
            $scopeConfig,
            $checkoutSession,
            $storeManager,
            $formKeyValidator,
            $cart
        );
        $this->resultLayoutFactory = $resultLayoutFactory;
        $this->jsonHelper = $jsonHelper;
        $this->couponFactory = $couponFactory;
        $this->quoteRepository = $quoteRepository;
        $this->escaper = $escaper;
    }

    /**
     * Initialize coupon
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        $couponCode = $this->getRequest()->getParam('minicart_remove') == 1
            ? ''
            : trim($this->getRequest()->getParam('minicart_coupon_code'));

        $cartQuote = $this->cart->getQuote();
        $oldCouponCode = $cartQuote->getCouponCode();

        $codeLength = strlen($couponCode);
        if (!$codeLength && !strlen($oldCouponCode)) {
            return;
        }

        try {
            $isCodeLengthValid = $codeLength && $codeLength <= \Magento\Checkout\Helper\Cart::COUPON_CODE_MAX_LENGTH;

            $itemsCount = $cartQuote->getItemsCount();
            if ($itemsCount) {
                $cartQuote->getShippingAddress()->setCollectShippingRates(true);
                $cartQuote->setCouponCode($isCodeLengthValid ? $couponCode : '')->collectTotals();
                $this->quoteRepository->save($cartQuote);
            }

            if ($codeLength) {
                $coupon = $this->couponFactory->create();
                $coupon->load($couponCode, 'code');
                if (!$itemsCount) {
                    if ($isCodeLengthValid && $coupon->getId()) {
                        $this->_checkoutSession->getQuote()->setCouponCode($couponCode)->save();
                        $successMessage =
                            __(
                                'You used coupon code "%1".',
                                $this->escaper->escapeHtml($couponCode)
                            );
                    } else {
                        $errorMessage =
                            __(
                                'The coupon code "%1" is not valid.',
                                $this->escaper->escapeHtml($couponCode)
                            );
                    }

                } else {
                    if ($isCodeLengthValid && $couponCode == $cartQuote->getCouponCode()) {
                        $successMessage =
                            __(
                                'You used coupon code "%1".',
                                $this->escaper->escapeHtml($couponCode)
                            );
                    } else {
                        $errorMessage =
                            __(
                                'The coupon code "%1" is not valid.',
                                $this->escaper->escapeHtml($couponCode)
                            );
                        $this->cart->save();
                    }
                }
            } else {
                $successMessage = __('You canceled the coupon code.');
            }
        } catch (LocalizedException $e) {
            $errorMessage = $e->getMessage();
        } catch (\Exception $e) {
            $errorMessage = __('We cannot apply the coupon code.');
        }
        $resultLayout = $this->resultLayoutFactory->create();
        $blockMiniCartCoupon = $resultLayout->getLayout()->createBlock('Magento\Checkout\Block\Cart\Coupon')
            ->setTemplate('MageVision_MiniCartCoupon::cart/minicart/coupon.phtml')
            ->toHtml();

        $message = isset($errorMessage) ? $errorMessage : $successMessage;
        $messageType = isset($errorMessage) ? 'errorMessage' : 'successMessage';
        $response = [
            'success' => true,
            'blockMiniCartCoupon' => $blockMiniCartCoupon,
            'message' => $message,
            $messageType => true
        ];
        return $this->getResponse()->representJson($this->jsonHelper->jsonEncode($response));
    }
}
