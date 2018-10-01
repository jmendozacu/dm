<?php
namespace DiamondMansion\Extensions\Override\Magento\Theme\Block\Html;

class Breadcrumbs extends \Magento\Theme\Block\Html\Breadcrumbs
{
    protected $_template = 'Magento_Theme::html/breadcrumbs.phtml';

    protected function _toHtml()
    {
        if (is_array($this->_crumbs)) {
            reset($this->_crumbs);
            $this->_crumbs[key($this->_crumbs)]['first'] = true;
            end($this->_crumbs);
            $this->_crumbs[key($this->_crumbs)]['last'] = true;
        }

        if ($this->getRequest()->getControllerName() == "category") {

            $registry = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Framework\Registry');
            $eavConfig = \Magento\Framework\App\ObjectManager::getInstance()->get('\Magento\Eav\Model\Config');
            
            end($this->_crumbs);
            $this->_crumbs[key($this->_crumbs)]['link'] = $registry->registry('current_category')->getUrl();
            $this->_crumbs[key($this->_crumbs)]['last'] = false;
            
            $params = array();
            $urlParams = array();
            foreach ($this->getRequest()->getParams() as $key=>$value) {
                if (in_array($key, array("dm_stone_type", "dm_stone_shape", "band", "metal", "setting_style", "design_collection"))) {
                    $params[$key] = $value;
                    $urlParams[$key] = "";
                }
            }
            
            $filterOrder = array("dm_stone_type", "band", "dm_stone_shape", "setting_style", "design_collection");
            
            $index = 0;

            foreach ($filterOrder as $fKey) {
                if (isset($params[$fKey])) {
                    
                    $attrLabel = $eavConfig->getAttribute('catalog_product', $fKey)->getSource()->getOptionText($params[$fKey]);
                    
                    $urlParams[$fKey] = $params[$fKey];
                    $urlSuffix = array("", "");
                    foreach ($urlParams as $uKey => $uValue) {
                        if ($uValue == "") continue;
                        $uLabel = strtolower(str_replace(" ", "-", $eavConfig->getAttribute('catalog_product', $uKey)->getSource()->getOptionText($uValue)));
                        $uLabel = $uLabel != 'natural' ?: 'diamond';
                        if ($index >= 2 && in_array($uKey, array("band", "design_collection", "metal"))) {
                            if ($urlSuffix[1] == "") {
                                $urlSuffix[1] = str_replace("_", "-", $uKey) . "=" . $uLabel;
                            } else {
                                $urlSuffix[1] .= "&" . str_replace("_", "-", $uKey) . "=" . $uLabel;
                            }
                        } else {
                            $urlSuffix[0] .= $uLabel . "/";
                        }
                    }
                    
                    switch ($fKey) {
                        case "dm_stone_type":
                            if ($attrLabel == "Natural") {
                                $label = "Diamond";
                            } else if ($attrLabel == "Setting") {
                                $label = "Settings";
                            } else {
                                $label = $attrLabel . " Diamond";
                            }
                            break;
                        case "band":
                            $label = "Bridal Set";
                            break;
                        case "dm_stone_shape":
                            if ($attrLabel == "Heart") {
                                $label = $attrLabel . " Shape";
                            } else {
                                $label = $attrLabel . " Cut";
                            }
                            break;
                        default:
                            $label = $attrLabel;
                            break;
                    }
                    
                    $link = $registry->registry("current_category")->getUrl() . $urlSuffix[0];
                    if ($urlSuffix[1] != "") { 
                        $link .= "?" . $urlSuffix[1];
                    }
                    
                    $this->_crumbs['filter'.$index] = array ("label"=>$label, "link"=>$link, "title"=>"", "first"=>"", "last"=>"", "readonly"=>"");
                    
                    $index ++;
                }
            }
            
            end($this->_crumbs);
            $this->_crumbs[key($this->_crumbs)]['link'] = "";
            $this->_crumbs[key($this->_crumbs)]['last'] = true;
            $this->_crumbs[key($this->_crumbs)]['readonly'] = true;
        }

        $this->assign('crumbs', $this->_crumbs);

        return \Magento\Framework\View\Element\Template::_toHtml();
    }
}
