<?php
/**
 * Created by PhpStorm.
 * User: May
 * Date: 8/20/2018
 * Time: 6:21 PM
 */

namespace DiamondMansion\Extensions\Block\Adminhtml\Designer\Edit;

/**
 * Class ResetButton
 */
class ResetButton implements \Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Reset'),
            'class' => 'reset',
            'on_click' => 'location.reload();',
            'sort_order' => 30
        ];
    }
}