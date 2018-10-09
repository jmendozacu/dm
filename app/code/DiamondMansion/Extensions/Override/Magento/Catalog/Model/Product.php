<?php

namespace DiamondMansion\Extensions\Override\Magento\Catalog\Model;

class Product extends \Magento\Catalog\Model\Product
{
    protected $_filters = [];

    public function setFilters($filters) {
        $this->_filters = $filters;
    }

    public function getFilters() {
        return $this->_filters;
    }

    public function getAllDmOptions() {
        return $this->getTypeInstance()->getAllDmOptions($this);
    }

    public function getDefaultDmOptions() {
        return $this->getTypeInstance()->getDefaultDmOptions($this);
    }

    public function getDmOptions() {
        return $this->getTypeInstance()->getDmOptions($this);
    }

    public function getName() {
        if (method_exists($this->getTypeInstance(), 'getName')) {
            return $this->getTypeInstance()->getName($this, parent::getName());
        } else {
            return parent::getName();
        }
    }

    public function getProductUrl($useSid = NULL) {
        if (method_exists($this->getTypeInstance(), 'getProductUrl')) {
            return $this->getTypeInstance()->getProductUrl($this);
        } else {
            return parent::getProductUrl($useSid);
        }
    }

    public function getImage() {
        if (method_exists($this->getTypeInstance(), 'getImage')) {
            return $this->getTypeInstance()->getImage($this);
        } else {
            return parent::getImage();
        }
    }
}