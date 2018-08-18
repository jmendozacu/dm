<?php
namespace DiamondMansion\Extensions\Controller\Adminhtml\Ring\Eternity\Price\Stone;

use \PhpOffice\PhpSpreadsheet\IOFactory;
use \Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \DiamondMansion\Extensions\Controller\Adminhtml\Base
{
    protected $resource;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\App\ResourceConnection $resource
    ) {
        parent::__construct($context, $resultPageFactory);

        $this->resource = $resource;
    }

    public function execute()
    {
        $_prices = $this->getRequest()->getParam("price");


        try {
            $readConnection = $this->resource->getConnection('core_read');
            $writeConnection = $this->resource->getConnection('core_write');
            $table = 'dm_eternity_ring_stone_price_entity';

            foreach ($_prices as $_cc=>$_cc_prices) {
                foreach ($_cc_prices as $_carat=>$_carat_prices) {
                    foreach ($_carat_prices as $_shape=>$_price) {
                        if (!$_price) $_price = 0.0000;
                        $_entity_id = $readConnection->fetchOne("SELECT entity_id From " . $table . " WHERE shape = '" . $_shape . "' AND carat = " . $_carat . " AND color_clarity = '" . $_cc . "' LIMIT 1");
                        if ($_entity_id) {
                            $writeConnection->query("UPDATE ".$table." SET price = ".$_price. " WHERE entity_id = " . $_entity_id);
                        } else {
                            $writeConnection->query("INSERT INTO ".$table." (shape, carat, color_clarity, price) VALUES ('" . $_shape . "', ". $_carat .", '" . $_cc . "', " . $_price . ")");
                        }
                    }
                }
            }

            $result = ['error' => false, 'message' => 'Successfully Saved!'];
        } catch (Exception $e) {
            $result = ['error' => true, 'message' => $e->getMessage()];
        }

        header('Content-type: application/json');
        echo json_encode($result);
    }
}