<?php
/**
 *
  * Copyright © 2018 Magenest. All rights reserved.
  * See COPYING.txt for license details.
  *
  * Magenest_InstagramShop extension
  * NOTICE OF LICENSE
  *
  * @category Magenest
  * @package  Magenest_InstagramShop
  * @author    dangnh@magenest.com

 */

namespace Magenest\InstagramShop\Block\Instagram\Renderer;

use Magenest\InstagramShop\Helper\Helper;
use Magenest\InstagramShop\Model\Photo;
use Magenest\InstagramShop\Model\TaggedPhoto;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Element\Template;

class Hotspot extends Template
{
    protected $_template = 'instagram/renderer/hotspot.phtml';
    /**
     * @var \Magenest\InstagramShop\Model\Hotspot
     */
    protected $hotspot;
    /**
     * @var Photo|TaggedPhoto
     */
    protected $photo;
    /**
     * @var int
     */
    protected $photoType;
    /**
     * @var Helper
     */
    protected $helper;
    /**
     * @var ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * Hotspot constructor.
     * @param Template\Context $context
     * @param Helper $helper
     * @param ProductRepositoryInterface $productRepository
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Helper $helper,
        ProductRepositoryInterface $productRepository,
        array $data = []
    )
    {
        $this->helper            = $helper;
        $this->productRepository = $productRepository;
        parent::__construct($context, $data);
    }

    /**
     * @param $hotspot \Magenest\InstagramShop\Model\Hotspot
     * @return Hotspot
     */
    private function setHotspot($hotspot)
    {
        $this->hotspot = $hotspot;
        return $this;
    }

    /**
     * @param Photo|TaggedPhoto $photo
     * @return $this
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;
        if ($photo !== null) {
            $this->setPhotoType($photo->getType());
            $hotspot = $this->helper->getHotspotByPhoto($photo->getPhotoId(), $photo->getType());
            $this->setHotspot($hotspot);
        }
        return $this;
    }

    /**
     * @param $photoId
     * @param int $type
     * @return $this
     */
    public function setPhotoByIdAndType($photoId, $type = 1)
    {
        $photo = $this->helper->getPhoto($photoId, $type);
        $this->setPhoto($photo);
        return $this;
    }

    /**
     * @return Photo|TaggedPhoto
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * @return \Magenest\InstagramShop\Model\Hotspot
     */
    public function getHotspot()
    {
        return $this->hotspot;
    }

    /**
     * @return int
     */
    public function getPhotoType()
    {
        return $this->photoType;
    }

    /**
     * @param int $type
     * @return Hotspot
     */
    private function setPhotoType($type)
    {
        $this->photoType = $type;
        return $this;
    }

    /**
     * @param Photo $photo
     * @param $x
     * @param $y
     * @return array
     */
    public function getPositionXY($photo, $x, $y)
    {
        return $this->helper->getPercentResolution($photo, $x, $y);
    }

    /**
     * @return array
     */
    public function getHotspotData()
    {
        $data = [];
        if ($hotspot = $this->getHotspot()) {
            for ($i = 1; $i <= 5; $i++) {
                $key    = \Magenest\InstagramShop\Model\Hotspot::KEY . $i;
                $xKey   = $key . '_x';
                $yKey   = $key . '_y';
                $skuKey = $key . '_sku';
                if (($photoModel = $this->getPhoto()) && ($x = $hotspot->getData($xKey)) && ($y = $hotspot->getData($yKey)) && ($sku = $hotspot->getData($skuKey))) {
                    try {
                        $product = $this->productRepository->get($sku);
                        $data[]  = [
                            'product' => $product,
                            'position' => $this->getPositionXY($photoModel, $x, $y)
                        ];
                    } catch (NoSuchEntityException $e) {
                        continue;
                    }
                }
            }
        }
        return $data;
    }
}