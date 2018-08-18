<?php
namespace DiamondMansion\Extensions\Controller\Adminhtml\Ring\Design\Price\Stone;

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
        $cert = "gia";

        $inputFileName = $this->filesystem->getDirectoryRead(DirectoryList::MEDIA)->getAbsolutePath() . '/xls_price/' .$cert.'.xls';

        $objReader = IOFactory::createReaderForFile($inputFileName);
        $objPHPExcel = $objReader->load($inputFileName);
        $sheetNames = $objPHPExcel->getSheetNames();

        $prices = array();

        foreach($sheetNames as $sheetIndex => $sheetName) {
            $shapeTemp = explode(" ", $sheetName);

            if (count($shapeTemp) > 1) {
                $shape = strtolower($shapeTemp[count($shapeTemp) - 1]);
            } else {
                $shape = strtolower($sheetName);
            }

            $objPHPExcel->setActiveSheetIndex($sheetIndex);

            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
            if (!isset($prices[$shape])) {
                $prices[$shape] = array();
            }
            foreach ($sheetData as $row) {
                if (is_numeric($row["A"])) {
                    if ($row["A"] > 0.6 && $row["A"] < 0.8) {
                        $carat = "0.75";
                    } else if ($row["A"] > 0.9 && $row["A"] < 1.1) {
                        $carat = "1.00";
                    } else if ($row["A"] > 1.1 && $row["A"] < 1.3) {
                        $carat = "1.25";
                    } else if ($row["A"] > 1.4 && $row["A"] < 1.6) {
                        $carat = "1.50";
                    } else if ($row["A"] > 1.6 && $row["A"] < 1.8) {
                        $carat = "1.75";
                    } else if ($row["A"] > 1.9 && $row["A"] < 2.1) {
                        $carat = "2.00";
                    } else if ($row["A"] > 2.1 && $row["A"] < 2.3) {
                        $carat = "2.25";
                    } else if ($row["A"] > 2.4 && $row["A"] < 2.6) {
                        $carat = "2.50";
                    } else if ($row["A"] > 2.6 && $row["A"] < 2.8) {
                        $carat = "2.75";
                    } else if ($row["A"] > 2.9 && $row["A"] < 3.1) {
                        $carat = "3.00";
                    } else {
                        $carat = "Invalid Carat";
                    }

                    if (!isset($prices[$shape][$carat]))
                        $prices[$shape][$carat] = array();
                    $clarities = array();
                } else if (in_array(strtolower($row["A"]), $this->helper->getDesignRingStoneColors())) {
                    $color = str_replace(" ", "-", strtolower($row["A"]));
                    if (!isset($prices[$shape][$carat][$color]))
                        $prices[$shape][$carat][$color] = array();
                    foreach ($clarities as $key=>$clarity) {
                        $price = 0;
                        if (!is_numeric($row[$key])) {
                            preg_match('/([0-9\,\.]+)/', $row[$key], $price);
                            $price = count($price)?(double)str_replace(",", "", $price[0]):0;
                        } else {
                            $price = (double)$row[$key];
                        }
                        $prices[$shape][$carat][$color][$clarity] = ($price == 0)?"":$price;
                    }
                } else if ($row["B"]) {
                    foreach ($row as $key=>$val) {
                        if ($key == "A") {
                            continue;
                        } else if ($val) {
                            if (strtolower($val) == "if") $val = "fl";
                            $clarities[$key] = strtolower($val);
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