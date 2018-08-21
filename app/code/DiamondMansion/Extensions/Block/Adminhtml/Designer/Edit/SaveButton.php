<?php
/**
 * Created by PhpStorm.
 * User: May
 * Date: 8/20/2018
 * Time: 6:21 PM
 */

namespace DiamondMansion\Extensions\Block\Adminhtml\Designer\Edit;

/**
 * Class SaveButton
 */
class SaveButton extends DefaultButton implements \Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Save Designer'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 90,
        ];
    }
}