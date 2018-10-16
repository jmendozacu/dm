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

    protected function injectAliases($routeUrl, array $aliases)
    {
        $result = rtrim($routeUrl, '/');
        if ($aliases) {
            $aliasesTmp = $aliases;
            $aliases= [];
            foreach ($this->filterOrders as $filter) {
                if (!isset($aliasesTmp[$filter])) {
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
                } else {
                    $result .= $delimiter . implode($this->aliasDelimiter, $alias);
                    $isFirstAlias = false;
                }
            }

            if (isset($this->query['_'])) {
                unset($this->query['_']);
            }
        }

        return $result . '/';
    }

    public function seofyUrl($url) {
        return str_replace('/?', '?', parent::seofyUrl($url));
    }
}