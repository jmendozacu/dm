<?php

namespace DiamondMansion\Extensions\Override\Mirasvit\Seo\Helper\Rewrite;

class Image extends \Mirasvit\Seo\Helper\Rewrite\Image
{
    public function getUrl()
    {
        if ($this->getProduct()->getIsCustomized() && method_exists($this->getProduct()->getTypeInstance(), 'getImage')) {
            return $this->getProduct()->getTypeInstance()->getImage($this->getProduct());
        }

        return parent::getUrl();
    }
}