<?php
namespace DiamondMansion\Extensions\Model\ResourceModel;

class ProductOptions extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{

    public function __construct(
        \Magento\Framework\Model\ResourceModel\Db\Context $context
    )
    {
        parent::__construct($context);
    }

    protected function _construct()
    {
        $this->_init('dm_product_options', 'entity_id');
    }
}