<?php
namespace DiamondMansion\Extensions\Model\ResourceModel;

class OptionsGroup extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('dm_options_group', 'entity_id');
    }
}