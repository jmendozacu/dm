<?php
namespace DiamondMansion\Extensions\Model\ResourceModel\Contact;

class Delivery extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('dm_contact_delivery', 'entity_id');
    }
}