<?php
/**
 * Created by PhpStorm.
 * User: May
 * Date: 8/20/2018
 * Time: 6:18 PM
 */

namespace DiamondMansion\Extensions\Block\Adminhtml\Designer\Edit;

/**
 * Class BackButton
 */
class BackButton extends DefaultButton implements \Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Back'),
            'on_click' => sprintf("location.href = '%s';", $this->getBackUrl()),
            'class' => 'back',
            'sort_order' => 10
        ];
    }

    /**
     * Get URL for back (reset) button
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/*/');
    }
}
