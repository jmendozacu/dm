<?php

namespace DiamondMansion\Extensions\Block\Product\View;

class LikeDislike extends \Magento\Catalog\Block\Product\View
{
    protected $_helper;
    protected $_scopeConfig;
    protected $_likedislikeFactory;
    protected $_eavConfig;
    protected $_storeInfo;
    protected $_session;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \DiamondMansion\Extensions\Helper\Image $helper,
        \DiamondMansion\Extensions\Model\LikeDislikeFactory $likedislikeFactory,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Store\Model\Information $storeInfo,
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Url\EncoderInterface $urlEncoder,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        \Magento\Framework\Stdlib\StringUtils $string,
        \Magento\Catalog\Helper\Product $productHelper,
        \Magento\Catalog\Model\ProductTypes\ConfigInterface $productTypeConfig,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Pricing\PriceCurrencyInterface $priceCurrency,
        \Magento\Framework\Session\SessionManagerInterface $session,
        array $data = []
    ) {
        $this->_helper = $helper;
        $this->_scopeConfig = $scopeConfig;
        $this->_likedislikeFactory = $likedislikeFactory;
        $this->_eavConfig = $eavConfig;
        $this->_storeInfo = $storeInfo;
        $this->_session = $session;

        parent::__construct(
            $context,
            $urlEncoder,
            $jsonEncoder,
            $string,
            $productHelper,
            $productTypeConfig,
            $localeFormat,
            $customerSession,
            $productRepository,
            $priceCurrency
        );
    }

    public function getHelper() {
        return $this->_helper;
    }

    public function getLikeDislikeStatus() {
        $guestEmail = $this->_session->getGuestEmail();

        $item = $this->_likedislikeFactory->create()->getCollection()
            ->addFieldToFilter('product_id', $this->getProduct()->getId())
            ->addFieldToFilter('email', $guestEmail)
            ->getFirstItem();

        $liked = 0;
        $disliked = 0;
        if ($item) {
            $liked = $item->getReview() ? 1 : 0;
            $disliked = $item->getReview() ? 0 : 1;
        }

        return [
            'liked' => $liked,
            'disliked' => $disliked
        ];
    }

    public function isLoggedIn() {
        return $this->customerSession->isLoggedIn();
    }
}