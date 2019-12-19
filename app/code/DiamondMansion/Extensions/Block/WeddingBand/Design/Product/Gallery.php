<?php

namespace DiamondMansion\Extensions\Block\WeddingBand\Design\Product;

use Magento\Framework\DataObject;

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
        $params = $this->getRequest()->getParams();

        $options = $this->_helper->getWeddingBandDesignOptions($this->getProduct(), $params);
        $defaultOptions = $options['defaultOptions'];

        $customImages = $this->_helper->getProductImages([
            'sku' => $this->getProduct()->getSku(),
            'metal' => $defaultOptions['metal']->getCode(),
            'width' => $defaultOptions['width']->getCode(),
            'finish' => $defaultOptions['finish']->getCode(),
        ], false);

        $images = [];
        foreach ($customImages as $customImage) {
            $images[] = [
                'thumb' => $customImage['thumb'],
                'img' => $customImage['main'],
                'full' => $customImage['pop'],
                'caption' => $this->getProduct()->getName(),
                'position' => 0,
                'isMain' => true,
                'type' => 'image',
                'videoUrl' => '',
                'isCustom' => true,
            ];
        }

        $product = $this->getProduct();
        $product->setIsCustomized(false);
        parent::setProduct($product);

        $imagesItems = [];
        /** @var DataObject $image */
        foreach ($this->getGalleryImages() as $image) {
            $imageItem = new DataObject([
                'thumb' => $image->getData('small_image_url'),
                'img' => $image->getData('medium_image_url'),
                'full' => $image->getUrl(),
                'caption' => ($image->getLabel() ?: $product->getName()),
                'position' => $image->getData('position'),
                'isMain'   => $this->isMainImage($image),
                'type' => str_replace('external-', '', $image->getMediaType()),
                'videoUrl' => $image->getVideoUrl(),
            ]);
            foreach ($this->getGalleryImagesConfig()->getItems() as $imageConfig) {
                $imageItem->setData(
                    $imageConfig->getData('json_object_key'),
                    $image->getData($imageConfig->getData('data_object_key'))
                );
            }
            $imagesItems[] = $imageItem->toArray();
        }

        if (empty($imagesItems) && count($customImages) == 0) {
            $imagesItems[] = [
                'thumb' => $this->_imageHelper->getDefaultPlaceholderUrl('thumbnail'),
                'img' => $this->_imageHelper->getDefaultPlaceholderUrl('image'),
                'full' => $this->_imageHelper->getDefaultPlaceholderUrl('image'),
                'caption' => '',
                'position' => '0',
                'isMain' => true,
                'type' => 'image',
                'videoUrl' => null,
            ];
        }

        $product->setIsCustomized(true);
        parent::setProduct($product);

        $images = array_merge($images, $imagesItems);

        return json_encode($images);
    }    
}