<?php

namespace DiamondMansion\Extensions\Override\Mirasvit\Seo\Observer;

class SeoDataObserver extends \Mirasvit\Seo\Observer\SeoDataObserver
{
    public function applyMeta(\Magento\Framework\Event\Observer $observer)
    {
        if ((!$seo = $this->seoData->getCurrentSeo())
            || $this->seoData->isIgnoredActions()) {
            return $seo;
        }
        $pageConfig = $this->context->getPageConfig();

        if ($seo->getMetaDescription()) {
            //Removes HTML tags and unnecessary whitespaces from Description Meta Tag
            $description = $seo->getMetaDescription();
            $description = $this->seoData->cleanMetaTag($description);
            $pageConfig->setDescription($description);
        }

        if ($seo->getMetaKeywords()) {
            $pageConfig->setKeywords($this->seoData->cleanMetaTag($seo->getMetaKeywords()));
        }
    }
}