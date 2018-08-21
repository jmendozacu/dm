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
class DeleteButton extends DefaultButton implements \Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->getId()) {
            $data = [
                'label' => __('Delete Designer'),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\''
                    . __('Are you sure you want to delete this designer?')
                    . '\', \'' . $this->getDeleteUrl() . '\')',
                'sort_order' => 20,
            ];
        }

        return $data;
    }

    /**
     * Get URL for back (reset) button
     *
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete', ['id' => $this->getId()]);
    }
}
