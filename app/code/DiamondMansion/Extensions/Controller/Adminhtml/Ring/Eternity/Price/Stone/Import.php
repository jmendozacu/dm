<?php
namespace DiamondMansion\Extensions\Controller\Adminhtml\Ring\Eternity\Price\Stone;

use \PhpOffice\PhpSpreadsheet\IOFactory;
use \Magento\Framework\App\Filesystem\DirectoryList;

class Import extends \DiamondMansion\Extensions\Controller\Adminhtml\Base
{
    protected $helper;
    protected $filesystem;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\Filesystem $filesystem,
        \DiamondMansion\Extensions\Helper\Data $helper
    ) {
        parent::__construct($context, $resultPageFactory);

        $this->filesystem = $filesystem;
        $this->helper = $helper;
    }

    public function execute()
    {
        $type = "";
        $cert = "eternity";

        $inputFileName = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath() . '/xls_price/' .$cert.'.xls';

        $objReader = IOFactory::createReaderForFile($inputFileName);
        $objPHPExcel = $objReader->load($inputFileName);
        $sheetNames = $objPHPExcel->getSheetNames();

        $prices = array();

        foreach($sheetNames as $sheetIndex => $sheetName) {
            $objPHPExcel->setActiveSheetIndex($sheetIndex);

            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

            foreach ($sheetData as $row) {
                if (in_array(strtolower($row["A"]), $this->helper->getEternityRingStoneColorClarities())) {
                    $color_clarity = str_replace(" ", "__", strtolower($row["A"]));

                    if (!isset($prices[$color_clarity]))
                        $prices[$color_clarity] = array();

                    $shapes = array();
                } else if (is_numeric($row["A"]) && (double)$row["A"] > 0 && (double)$row["A"] <= 1) {
                    $carat = (double)$row["A"] . "";
                    if (!isset($prices[$color_clarity][$carat]))
                        $prices[$color_clarity][$carat] = array();
                    foreach ($shapes as $key=>$shape) {
                        $price = 0;
                        if (!is_numeric($row[$key])) {
                            preg_match('/([0-9\,\.]+)/', $row[$key], $price);
                            $price = count($price)?(double)str_replace(",", "", $price[0]):0;
                        } else {
                            $price = (double)$row[$key];
                        }
                        $prices[$color_clarity][$carat][$shape] = ($price == 0)?"":$price;
                    }
                } else if (isset($row["B"])) {
                    foreach ($row as $key=>$val) {
                        if ($key == "A") {
                            continue;
                        } else if ($val) {
                            $shapes[$key] = trim(strtolower($val));
                        } else {
                            break;
                        }
                    }
                }
            }
        }

        header('Content-type: application/json');
        echo json_encode($prices);
    }
}