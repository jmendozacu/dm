<?php

namespace DiamondMansion\Extensions\Override\Amasty\ShopbySeo\Helper;

class Url extends \Amasty\ShopbySeo\Helper\Url
{
    protected $filterOrders = [
        'dm_band',
        'dm_stone_shape',
        'dm_stone_type',
        'dm_design_collection',
        'dm_designer',
        'dm_metal',
        'dm_setting_style'
    ];

    protected $unnecessaryParams = [
        '_',
        'band',
        'metal',
        'design-collection'
    ];

    protected function injectAliases($routeUrl, array $aliases)
    {
        $result = rtrim($routeUrl, '/');
        foreach ($this->unnecessaryParams as $param) {
            if (isset($this->query[$param])) {
                unset($this->query[$param]);
            }
        }
        if ($aliases) {
            $aliasesTmp = $aliases;
            $aliases= [];
            foreach ($this->filterOrders as $filter) {
                if (!isset($aliasesTmp[$filter]) || !$aliasesTmp[$filter]) {
                    continue;
                }

                $aliases[$filter] = $aliasesTmp[$filter];
            }

            $filterWord = $this->helper->getFilterWord() ? $this->helper->getFilterWord() . DIRECTORY_SEPARATOR : '';

            if ($this->coreRegistry->registry('amasty_shopby_root_category_index')
                && isset($aliases[$this->brandAttributeCode])
            ) {
                $result .= DIRECTORY_SEPARATOR . implode($this->aliasDelimiter, $aliases[$this->brandAttributeCode]);
                unset($aliases[$this->brandAttributeCode]);
            }

            if (count($aliases) > 0) {
                $result .= DIRECTORY_SEPARATOR . $filterWord;
            }

            $diff = array_diff(array("dm_metal", "dm_band", "dm_design_collection"), array_keys($aliases));

            $isFirstAlias = true;
            foreach ($aliases as $code => $alias) {
                $delimiter = $isFirstAlias ? '' : $this->aliasDelimiter;

                if ((count($aliases) > 2 && in_array($code, array("dm_metal", "dm_band", "dm_design_collection")) && (count($diff) || count($aliases) != 3)) ||
                    (count($aliases) == 3 && in_array($code, array("dm_metal", "dm_design_collection")) && count($diff) == 0)
                ) {
                    $code = str_replace(['dm_', '_'], ['', '-'], $code);
                    $this->query[$code] = implode($this->aliasDelimiter, $alias);
                    if (!$this->query[$code]) {
                        unset($this->query[$code]);
                    }
                } else {
                    $result .= $delimiter . implode($this->aliasDelimiter, $alias);
                    $isFirstAlias = false;
                }
            }
        }

        return $result . '/';
    }

    public function seofyUrl($url) {
        if (!$this->initialize($url) || $this->cmsManager->isCmsPageNavigation()) {
            return $url;
        }

        $this->query = $this->parseQuery();

        if (isset($this->query['options']) && $this->query['options'] == 'cart') {
            return $url;
        }

        $routeUrl = $this->originalParts['route'];

        $moduleName = $this->_getRequest()->getModuleName();
        $settingCategory = $this->settingHelper->getSettingByAttributeCode(\Amasty\Shopby\Helper\Category::ATTRIBUTE_CODE);
        $fromRootToCategory = isset($this->query['cat'])
            && (in_array($moduleName, ['catalog', 'amshopby', 'cms', 'ambrand']))
            && !$settingCategory->isMultiselect();
        if ($fromRootToCategory) {
            $routeUrl = $this->getCategoryRouteUrl() ?: $routeUrl;
        }

        if ($this->coreRegistry->registry('amasty_shopby_root_category_index')
            && $this->query
            && !$fromRootToCategory
        ) {
            $routeUrl = $this->rootRoute;
            $isShopby = true;
        } else {
            $isShopby = false;
        }

        $routeUrlTrimmed = $this->removeCategorySuffix($routeUrl);
        $endsWithLine = strlen($routeUrlTrimmed)
            && $routeUrlTrimmed[strlen($routeUrlTrimmed) - 1] == DIRECTORY_SEPARATOR;
        if ($endsWithLine) {
            //if routeUrl is valid Magento route
            //return $url;
        }

        $moveSuffix = $routeUrlTrimmed != $routeUrl;
        $resultPath = $routeUrlTrimmed;

        $seoAliases = $this->cutAliases();
        foreach ($seoAliases as $aliases) {
            foreach ($aliases as $key => $alias) {
                if ($alias == $routeUrl) {
                    unset($seoAliases[$key]);
                }
            }
        }

        if ($seoAliases) {
            $resultPath = $this->injectAliases($resultPath, $seoAliases);
        }

        $resultPath = $this->cutReplaceExtraShopby($resultPath);
        $resultPath = ltrim($resultPath, DIRECTORY_SEPARATOR);

        if ($moveSuffix || ($isShopby && $this->appendShopbySuffix)) {
            $resultPath = $this->addCategorySuffix($resultPath);
        }

        if (isset($this->query['_'])) {
            unset($this->query['_']);
        }

        $result = $this->query ? ($resultPath . '?' . $this->query2Params($this->query)) : $resultPath;

        if ($this->originalParts['hash']) {
            $result .= '#' . $this->originalParts['hash'];
        }

        if (strpos($this->originalParts['domain'] . $result, '/?option=') !== false ||
            strpos($this->originalParts['domain'] . $result, '/?utm_source=') !== false) {
            return $this->originalParts['domain'] . $result;
        }
        return str_replace('/?', '?', $this->originalParts['domain'] . $result);
    }
}