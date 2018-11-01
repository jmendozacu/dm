<?php

namespace DiamondMansion\Extensions\Block\Product\View;

class LikeDislike extends \Magento\Catalog\Block\Product\View
{
    protected $_helper;
    protected $_scopeConfig;
    protected $_likedislikeFactory;
    protected $_eavConfig;
    protected $_storeInfo;

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
        array $data = []
    ) {
        $this->_helper = $helper;
        $this->_scopeConfig = $scopeConfig;
        $this->_likedislikeFactory = $likedislikeFactory;
        $this->_eavConfig = $eavConfig;
        $this->_storeInfo = $storeInfo;

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
        $ipaddress = '';

        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';

        $item = $this->_likedislikeFactory->create()->getCollection()
            ->addFieldToFilter('product_id', $this->getProduct()->getId())
            ->addFieldToFilter('customer_ip', $ipaddress)
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
}