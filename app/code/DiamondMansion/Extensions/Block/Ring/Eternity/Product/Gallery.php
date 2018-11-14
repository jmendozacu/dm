<?php

namespace DiamondMansion\Extensions\Block\Ring\Eternity\Product;

class Gallery extends \Magento\Catalog\Block\Product\View\Gallery
{
    protected $_helper;
    protected $_scopeConfig;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \DiamondMansion\Extensions\Helper\Image $helper,
        \Magento\Catalog\Block\Product\Context $context,
        \Magento\Framework\Stdlib\ArrayUtils $arrayUtils,
        \Magento\Framework\Json\EncoderInterface $jsonEncoder,
        array $data = []
    ) {
        $this->_helper = $helper;
        $this->_scopeConfig = $scopeConfig;

        parent::__construct(
            $context,
            $arrayUtils,
            $jsonEncoder,
            $data
        );
    }

    public function getGalleryImagesJson() {
        $images = json_decode(parent::getGalleryImagesJson(), true);
        
        $params = $this->getRequest()->getParams();

        $options = $this->_helper->getRingEternityOptions($this->getProduct(), $params);
        $defaultOptions = $options['defaultOptions'];

        $defaultImage = $this->_helper->getProductImages([
            'sku' => $this->getProduct()->getSku(),
            'type' => $defaultOptions['stone-type']->getCode(),
            'shape' => $defaultOptions['stone-shape']->getCode(),
            'carat' => $defaultOptions['stone-carat']->getCode(),
            'metal' => $defaultOptions['metal']->getCode(),
        ]);
        
        $images = array_merge([[
            'thumb' => $defaultImage['thumb'],
            'img' => $defaultImage['main'],
            'full' => $defaultImage['pop'],
            'caption' => $this->getProduct()->getName(),
            'position' => 0,
            'isMain' => true,
            'type' => 'image',
            'videoUrl' => '',
        ]], $images);

        return json_encode($images);
    }    
}