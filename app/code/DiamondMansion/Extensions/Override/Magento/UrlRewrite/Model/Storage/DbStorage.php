<?php

namespace DiamondMansion\Extensions\Override\Magento\UrlRewrite\Model\Storage;

use Magento\UrlRewrite\Service\V1\Data\UrlRewrite;

class DbStorage extends \Magento\UrlRewrite\Model\Storage\DbStorage
{
    protected function doFindOneByData(array $data) {
        if (isset($data[UrlRewrite::REQUEST_PATH])) {
            $data[UrlRewrite::REQUEST_PATH] = rtrim($data[UrlRewrite::REQUEST_PATH], '/') . '/';
            $eavAttribute = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Eav\Model\Config')->getAttribute('catalog_product', 'dm_stone_shape');
            foreach ($eavAttribute->getSource()->getAllOptions() as $eavOption) {
                $shape = strtolower(str_replace(" ", "-", $eavOption['label']));
                $prefix = ($shape == 'heart') ? $shape . '-shape-' : $shape . '-cut-';
                if (strpos($data[UrlRewrite::REQUEST_PATH], $prefix) === 0) {
                    $data[UrlRewrite::REQUEST_PATH] = rtrim(substr($data[UrlRewrite::REQUEST_PATH], strlen($prefix)), '/') . '/';
                }
            }
        }

        return parent::doFindOneByData($data);
    }
}