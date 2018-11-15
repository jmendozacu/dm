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

    public function getAllDmOptions($sort = false) {
        return $this->getTypeInstance()->getAllDmOptions($this, $sort);
    }

    public function getDefaultDmOptions($sort = false) {
        return $this->getTypeInstance()->getDefaultDmOptions($this, $sort);
    }

    public function setDefaultDmOptions($options) {
        $this->getTypeInstance()->setDefaultDmOptions($this, $options);
    }

    public function getDmOptions($sort = false) {
        return $this->getTypeInstance()->getDmOptions($this, $sort);
    }

    public function getDmName() {
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

    public function getDefaultImage() {
        return parent::getImage();
    }
}