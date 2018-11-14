<?php

namespace DiamondMansion\Extensions\Block\Ring\Eternity\Cart\Item;

class Renderer extends \Magento\Checkout\Block\Cart\Item\Renderer
{
    public function getProductUrl() {
        return $this->getProduct()->getProductUrl();
    }
}