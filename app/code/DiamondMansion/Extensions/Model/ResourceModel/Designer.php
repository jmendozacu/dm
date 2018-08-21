<?php
namespace DiamondMansion\Extensions\Model\ResourceModel;

class Designer extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('dm_designer', 'entity_id');
    }
}