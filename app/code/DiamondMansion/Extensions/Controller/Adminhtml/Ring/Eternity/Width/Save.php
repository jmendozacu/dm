<?php
/**
 * Created by May.
 * User: May
 * Date: 8/19/2018
 * Time: 2:51 PM
 */

namespace DiamondMansion\Extensions\Controller\Adminhtml\Ring\Eternity\Width;

use \PhpOffice\PhpSpreadsheet\IOFactory;
use \Magento\Framework\App\Filesystem\DirectoryList;

class Save extends \DiamondMansion\Extensions\Controller\Adminhtml\Base
{
    protected $resource;
    protected $scopeConfig;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        parent::__construct($context, $resultPageFactory);

        $this->resource = $resource;
        $this->scopeConfig = $scopeConfig;
    }

    public function execute()
    {
        $widths = $this->getRequest()->getParam("widths");

        try {
            $readConnection = $this->resource->getConnection('core_read');
            $writeConnection = $this->resource->getConnection('core_write');
            $table = $this->resource->getTableName('core_config_data');

            foreach ($widths as $carat => $shape_widths) {
                foreach ($shape_widths as $shape => $width) {
                    $path = 'dm/eternity/width/' . $shape . '/' . $carat;
                    $value = $this->scopeConfig->getValue($path);
                    if ($value) {
                        $writeConnection->query("UPDATE " . $table . " SET value = " . $width . " WHERE path = '" . $path . "'");
                    } else {
                        $writeConnection->query("INSERT INTO " . $table . " (scope, scope_id, path, value) VALUES ('default', 0, '" . $path . "', '" . $width . "')");
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