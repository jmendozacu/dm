<?php

namespace DiamondMansion\Extensions\Console\Command;

use Magento\Framework\App\ObjectManager\ConfigLoader;
use Magento\Framework\App\ObjectManagerFactory;
use Magento\Framework\App\State;
use Magento\Framework\App\ResourceConnection;
use Magento\Store\Model\StoreManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class AmshopbyCustomPages extends Command
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $_objectManager;
    protected $_connection;
    protected $_eavConfig;

    public function __construct(
        ObjectManagerFactory $objectManagerFactory,
        ResourceConnection $resource,
        \Magento\Eav\Model\Config $eavConfig
    ) {
        $params = $_SERVER;
        $params[StoreManager::PARAM_RUN_CODE] = 'admin';
        $params[StoreManager::PARAM_RUN_TYPE] = 'store';

        $this->_objectManager = $objectManagerFactory->create($params);
        $this->_connection = $resource->getConnection();
        $this->_eavConfig = $eavConfig;

        parent::__construct();
    }   
    
    protected function configure()
    {
        $this->setName('dm:amshopby:custompages')
            ->setDescription('Generate Amshopby Custom Pages...');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Starting...</info>');

        $this->generateEngagementRingCustomPages($output);

        $output->writeln('<info>Finished.</info>');

        return 0;
    }

    public function generateEngagementRingCustomPages($output) {
        $output->writeln('Remove Engagement Ring Custom Pages...');
        $rows = $this->_connection->fetchAll("SELECT * from amasty_amshopby_page WHERE categories = 26");
        foreach ($rows as $row) {
            $this->_connection->query("DELETE FROM amasty_amshopby_page_store WHERE page_id = " . $row['page_id']);
        }
        $this->_connection->query("DELETE FROM amasty_amshopby_page WHERE categories = 26");

        $mainAttributeCodes = array("dm_stone_type", "dm_stone_shape", "dm_metal", "dm_band", "dm_setting_style", "dm_design_collection");
        
        $mainAttributeValues = array();
        foreach ($mainAttributeCodes as $attribueCode) {
            $mainAttributeValues[$attribueCode] = array();
            $attr = $this->_eavConfig->getAttribute('catalog_product', $attribueCode);
            foreach ($attr->getSource()->getAllOptions() as $attrOption) {
                $mainAttributeValues[$attribueCode][$attrOption['label']] = $attrOption['value'];
            }
        }

        $output->writeln('Level 1...');
        $this->generateEngagementRingCustomPagesLevel1($mainAttributeValues);
        $output->writeln('Level 2...');
        $this->generateEngagementRingCustomPagesLevel2($mainAttributeValues);
        $output->writeln('Level 3...');
        $this->generateEngagementRingCustomPagesLevel3($mainAttributeValues);
        $output->writeln('Level 4...');
        $this->generateEngagementRingCustomPagesLevel4($mainAttributeValues);

        $query = "INSERT INTO amasty_amshopby_page_store (`page_id`, `store_id`) SELECT page_id, 0 FROM amasty_amshopby_page WHERE categories='26'";
        $this->_connection->query($query);
    }

    public function generateEngagementRingCustomPagesLevel1($mainAttributeValues) {
        foreach ($mainAttributeValues as $code=>$values) {
            foreach ($values as $attrLabel=>$value) {
                if ($value == "") {
                    continue;
                }
                $pageTitle = "Engagement Rings";
                
                switch ($code) {
                    case "dm_stone_shape":
                        if ($attrLabel == "Heart") {
                            $pageTitle = "Heart Shape " . $pageTitle;
                        } else {
                            $pageTitle = $attrLabel . " cut " . $pageTitle;
                        }
                        break;
                    case "dm_stone_type":
                        if ($attrLabel == "Diamond") {
                            $pageTitle = "Diamond " . $pageTitle;
                        } else if ($attrLabel == "Setting") {
                            $pageTitle = "Engagement Ring Settings";
                        } else {
                            $pageTitle = $attrLabel . " Diamond " . $pageTitle;
                        }
                        break;
                    case "dm_setting_style":
                    case "dm_design_collection":
                    case "dm_metal":
                        $pageTitle = $attrLabel . " " . $pageTitle;
                        break;
                    case "dm_band":
                        if ($attrLabel != "No Band") {
                            $pageTitle = "Bridal Wedding Ring Sets";
                        }
                        break;
                }
                
                $cond = array(
                    array(
                        "filter"=>$code,
                        "value"=>array($value)
                    ),
                );
                
                $query = "INSERT INTO amasty_amshopby_page (`position`, `title`, `description`, `meta_title`, `meta_keywords`, `meta_description`, `conditions`, `categories`) VALUES ('replace', '" . $pageTitle . "', '".$pageTitle."', '".$pageTitle."', '', '".$pageTitle."', '".serialize($cond)."', 26)";
                $this->_connection->query($query);
            }
        }
    }

    public function generateEngagementRingCustomPagesLevel2($mainAttributeValues) {
        foreach ($mainAttributeValues as $code=>$values) {
            unset($mainAttributeValues[$code]);
            $mainAttributeValues2 = $mainAttributeValues;
            
            foreach ($mainAttributeValues2 as $code2=>$values2) {
                foreach ($values as $attrLabel=>$value) {
                    if ($value == "") { continue; }
                    foreach ($values2 as $attrLabel2=>$value2) {
                        if ($value2 == "") { continue; }
                        $pageTitle = "Engagement Rings";
                        $params = array($code=>$value, $code2=>$value2);

                        if (isset($params["dm_stone_shape"]) && isset($params["dm_stone_type"])) {
                            $attrLabel1 = $this->_eavConfig->getAttribute('catalog_product', "dm_stone_shape")->getSource()->getOptionText($params["dm_stone_shape"]);
                            $attrLabel2 = $this->_eavConfig->getAttribute('catalog_product', "dm_stone_type")->getSource()->getOptionText($params["dm_stone_type"]);
                            if ($attrLabel1 == "Heart") {
                                $pageTitle = $attrLabel1 . " Shape " . $attrLabel2 . " Diamond " . $pageTitle;
                            } else {
                                $pageTitle = $attrLabel1 . " cut " . $attrLabel2 . " Diamond " . $pageTitle;
                            }
                        } else if (isset($params["dm_stone_shape"]) && isset($params["dm_setting_style"])) {
                            $attrLabel1 = $this->_eavConfig->getAttribute('catalog_product', "dm_stone_shape")->getSource()->getOptionText($params["dm_stone_shape"]);
                            $attrLabel2 = $this->_eavConfig->getAttribute('catalog_product', "dm_setting_style")->getSource()->getOptionText($params["dm_setting_style"]);
                            if ($attrLabel1 == "Heart") {
                                $pageTitle = $attrLabel1 . " Shape " . $attrLabel2 . " " . $pageTitle;
                            } else {
                                $pageTitle = $attrLabel1 . " cut " . $attrLabel2 . " " . $pageTitle;
                            }
                        } else if (isset($params["dm_stone_shape"]) && isset($params["dm_design_collection"])) {
                            $attrLabel1 = $this->_eavConfig->getAttribute('catalog_product', "dm_stone_shape")->getSource()->getOptionText($params["dm_stone_shape"]);
                            $attrLabel2 = $this->_eavConfig->getAttribute('catalog_product', "dm_design_collection")->getSource()->getOptionText($params["dm_design_collection"]);
                            if ($attrLabel1 == "Heart") {
                                $pageTitle = $attrLabel2 . " " . $attrLabel1 . " Shape " . $pageTitle;
                            } else {
                                $pageTitle = $attrLabel2 . " " . $attrLabel1 . " cut " . $pageTitle;
                            }
                        } else if (isset($params["dm_stone_shape"]) && isset($params["dm_metal"])) {
                            $attrLabel1 = $this->_eavConfig->getAttribute('catalog_product', "dm_stone_shape")->getSource()->getOptionText($params["dm_stone_shape"]);
                            $attrLabel2 = $this->_eavConfig->getAttribute('catalog_product', "dm_metal")->getSource()->getOptionText($params["dm_metal"]);
                            if ($attrLabel1 == "Heart") {
                                $pageTitle = $attrLabel2 . " " . $attrLabel1 . " Shape " . $pageTitle;
                            } else {
                                $pageTitle = $attrLabel2 . " " . $attrLabel1 . " cut " . $pageTitle;
                            }
                        } else if (isset($params["dm_stone_shape"]) && isset($params["dm_band"])) {
                            $attrLabel1 = $this->_eavConfig->getAttribute('catalog_product', "dm_stone_shape")->getSource()->getOptionText($params["dm_stone_shape"]);
                            if ($attrLabel1 == "Heart") {
                                $pageTitle = $attrLabel1 . " Shape Bridal Wedding Ring Sets";
                            } else {
                                $pageTitle = $attrLabel1 . " cut Bridal Wedding Ring Sets";
                            }
                        } else if (isset($params["dm_stone_type"]) && isset($params["dm_design_collection"])) {
                            $attrLabel1 = $this->_eavConfig->getAttribute('catalog_product', "dm_stone_type")->getSource()->getOptionText($params["dm_stone_type"]);
                            $attrLabel2 = $this->_eavConfig->getAttribute('catalog_product', "dm_design_collection")->getSource()->getOptionText($params["dm_design_collection"]);
                            $pageTitle = $attrLabel2 . " " . $attrLabel1 . " Diamond " . $pageTitle;
                        } else if (isset($params["dm_stone_type"]) && isset($params["dm_setting_style"])) {
                            $attrLabel1 = $this->_eavConfig->getAttribute('catalog_product', "dm_stone_type")->getSource()->getOptionText($params["dm_stone_type"]);
                            $attrLabel2 = $this->_eavConfig->getAttribute('catalog_product', "dm_setting_style")->getSource()->getOptionText($params["dm_setting_style"]);
                            $pageTitle = $attrLabel2 . " " . $attrLabel1 . " Diamond " . $pageTitle;
                        } else if (isset($params["dm_stone_type"]) && isset($params["dm_metal"])) {
                            $attrLabel1 = $this->_eavConfig->getAttribute('catalog_product', "dm_stone_type")->getSource()->getOptionText($params["dm_stone_type"]);
                            $attrLabel2 = $this->_eavConfig->getAttribute('catalog_product', "dm_metal")->getSource()->getOptionText($params["dm_metal"]);
                            $pageTitle = $attrLabel2 . " " . $attrLabel1 . " Diamond " . $pageTitle;
                        } else if (isset($params["dm_stone_type"]) && isset($params["dm_band"])) {
                            $attrLabel1 = $this->_eavConfig->getAttribute('catalog_product', "dm_stone_type")->getSource()->getOptionText($params["dm_stone_type"]);
                            $pageTitle = $attrLabel1 . " Diamond Bridal Wedding Ring Sets";
                        } else if (isset($params["dm_setting_style"]) && isset($params["dm_design_collection"])) {
                            $attrLabel1 = $this->_eavConfig->getAttribute('catalog_product', "dm_setting_style")->getSource()->getOptionText($params["dm_setting_style"]);
                            $attrLabel2 = $this->_eavConfig->getAttribute('catalog_product', "dm_design_collection")->getSource()->getOptionText($params["dm_design_collection"]);
                            $pageTitle = $attrLabel2 . " " . $attrLabel1 . " " . $pageTitle;
                        } else if (isset($params["dm_setting_style"]) && isset($params["dm_metal"])) {
                            $attrLabel1 = $this->_eavConfig->getAttribute('catalog_product', "dm_setting_style")->getSource()->getOptionText($params["dm_setting_style"]);
                            $attrLabel2 = $this->_eavConfig->getAttribute('catalog_product', "dm_metal")->getSource()->getOptionText($params["dm_metal"]);
                            $pageTitle = $attrLabel2 . " " . $attrLabel1 . " " . $pageTitle;
                        } else if (isset($params["dm_setting_style"]) && isset($params["dm_band"])) {
                            $attrLabel1 = $this->_eavConfig->getAttribute('catalog_product', "dm_setting_style")->getSource()->getOptionText($params["dm_setting_style"]);
                            $pageTitle = $attrLabel1 . " Bridal Wedding Ring Sets";
                        } else if (isset($params["dm_metal"]) && isset($params["dm_band"])) {
                            $attrLabel1 = $this->_eavConfig->getAttribute('catalog_product', "dm_metal")->getSource()->getOptionText($params["dm_metal"]);
                            $pageTitle = $attrLabel1 . " Bridal Wedding Ring Sets";
                        } else if (isset($params["dm_metal"]) && isset($params["dm_design_collection"])) {
                            $attrLabel1 = $this->_eavConfig->getAttribute('catalog_product', "dm_metal")->getSource()->getOptionText($params["dm_metal"]);
                            $attrLabel2 = $this->_eavConfig->getAttribute('catalog_product', "dm_design_collection")->getSource()->getOptionText($params["dm_design_collection"]);
                            $pageTitle = $attrLabel2 . " " . $attrLabel1 . " " . $pageTitle;
                        } else if (isset($params["dm_band"]) && isset($params["dm_design_collection"])) {
                            $attrLabel1 = $this->_eavConfig->getAttribute('catalog_product', "dm_design_collection")->getSource()->getOptionText($params["dm_design_collection"]);
                            $pageTitle = $attrLabel1 . " Bridal Wedding Ring Sets";
                        }
                        
                        $pageTitle = str_replace("Diamond Diamond ", "Diamond ", $pageTitle);
                        $pageTitle = str_replace("Setting Diamond Engagement Rings", "Engagement Ring Settings", $pageTitle);
                        $pageTitle = str_replace("Setting Diamond ", "", $pageTitle);

                        $cond = array(
                            array(
                                "filter"=>$code,
                                "value"=>array($value)
                            ),
                            array(
                                "filter"=>$code2,
                                "value"=>array($value2)
                            ),
                        );
                        
                        $query = "INSERT INTO amasty_amshopby_page (`position`, `title`, `description`, `meta_title`, `meta_keywords`, `meta_description`, `conditions`, `categories`) VALUES ('replace', '" . $pageTitle . "', '".$pageTitle."', '".$pageTitle."', '', '".$pageTitle."', '".serialize($cond)."', 26)";
                        $this->_connection->query($query);
                    }
                }
            }
        }
    }

    public function generateEngagementRingCustomPagesLevel3($mainAttributeValues) {
        foreach ($mainAttributeValues as $code=>$values) {
            unset($mainAttributeValues[$code]);
            $mainAttributeValues2 = $mainAttributeValues;
            foreach ($mainAttributeValues2 as $code2=>$values2) {
                unset($mainAttributeValues2[$code2]);
                $mainAttributeValues3 = $mainAttributeValues2;
                foreach ($mainAttributeValues3 as $code3=>$values3) {
                    foreach ($values as $label=>$value) {
                        if ($value == "") { continue; }
                        foreach ($values2 as $label2=>$value2) {
                            if ($value2 == "") { continue; }
                            foreach ($values3 as $label3=>$value3) {
                                if ($value3 == "") { continue; }
                                
                                $params = array(
                                    $code=>array("label"=>$label, "value"=>$value), 
                                    $code2=>array("label"=>$label2, "value"=>$value2), 
                                    $code3=>array("label"=>$label3, "value"=>$value3), 
                                );
                                
                                $pageTitle = "";
                                
                                if (isset($params["dm_design_collection"])) {
                                    $pageTitle .= $params["dm_design_collection"]["label"];
                                }
                                
                                if (isset($params["dm_stone_shape"])) {
                                    $pageTitle .= " " . $params["dm_stone_shape"]["label"];
                                    if ($params["dm_stone_shape"]["label"] == "Heart") {
                                        $pageTitle .= " Shape";
                                    } else {
                                        $pageTitle .= " Cut";
                                    }
                                }
                                
                                if (isset($params["dm_setting_style"])) {
                                    $pageTitle .= " " . $params["dm_setting_style"]["label"];
                                }
                                
                                if (isset($params["dm_stone_type"])) {
                                    if ($params["dm_stone_type"]["label"] == "Setting") {
                                        $pageTitle .= " Engagement Ring Settings";
                                    } else {
                                        if ($params["dm_stone_type"]["label"] != "Diamond") {
                                            $pageTitle .= " " . $params["dm_stone_type"]["label"];
                                        }
                                        
                                        $pageTitle .= " Diamond";
                                    }
                                }
                                
                                if (!isset($params["dm_stone_type"]) || $params["dm_stone_type"]["label"] != "Setting") {
                                    if (isset($params["dm_band"]) && $params["dm_band"]["label"] != "No Band") {
                                        $pageTitle .= " Bridal Wedding Ring Set";
                                    } else {
                                        $pageTitle .= " Engagement Rings";
                                    }
                                }
                                
                                if (isset($params["dm_metal"])) {
                                    $pageTitle .= " - " . $params["dm_metal"]["label"];
                                }
                                
                                $pageTitle = trim($pageTitle);

                                $cond = array(
                                    array(
                                        "filter"=>$code,
                                        "value"=>array($value)
                                    ),
                                    array(
                                        "filter"=>$code2,
                                        "value"=>array($value2)
                                    ),
                                    array(
                                        "filter"=>$code3,
                                        "value"=>array($value3)
                                    ),
                                );
                                
                                $query = "INSERT INTO amasty_amshopby_page (`position`, `title`, `description`, `meta_title`, `meta_keywords`, `meta_description`, `conditions`, `categories`) VALUES ('replace', '" . $pageTitle . "', '".$pageTitle."', '".$pageTitle."', '', '".$pageTitle."', '".serialize($cond)."', 26)";
                                $this->_connection->query($query);
                            }
                        }
                    }
                }
            }
        }
    }
    public function generateEngagementRingCustomPagesLevel4($mainAttributeValues) {
        foreach ($mainAttributeValues as $code=>$values) {
            unset($mainAttributeValues[$code]);
            $mainAttributeValues2 = $mainAttributeValues;
            foreach ($mainAttributeValues2 as $code2=>$values2) {
                unset($mainAttributeValues2[$code2]);
                $mainAttributeValues3 = $mainAttributeValues2;
                foreach ($mainAttributeValues3 as $code3=>$values3) {
                    unset($mainAttributeValues3[$code3]);
                    $mainAttributeValues4 = $mainAttributeValues3;
                    foreach ($mainAttributeValues4 as $code4=>$values4) {
                        foreach ($values as $label=>$value) {
                            if ($value == "") { continue; }
                            foreach ($values2 as $label2=>$value2) {
                                if ($value2 == "") { continue; }
                                foreach ($values3 as $label3=>$value3) {
                                    if ($value3 == "") { continue; }
                                    foreach ($values4 as $label4=>$value4) {
                                        if ($value4 == "") { continue; }
                                        
                                        $params = array(
                                            $code=>array("label"=>$label, "value"=>$value), 
                                            $code2=>array("label"=>$label2, "value"=>$value2), 
                                            $code3=>array("label"=>$label3, "value"=>$value3), 
                                            $code4=>array("label"=>$label4, "value"=>$value4), 
                                        );
                                        
                                        $pageTitle = "";
                                        
                                        if (isset($params["dm_design_collection"])) {
                                            $pageTitle .= $params["dm_design_collection"]["label"];
                                        }
                                        
                                        if (isset($params["dm_stone_shape"])) {
                                            $pageTitle .= " " . $params["dm_stone_shape"]["label"];
                                            if ($params["dm_stone_shape"]["label"] == "Heart") {
                                                $pageTitle .= " Shape";
                                            } else {
                                                $pageTitle .= " Cut";
                                            }
                                        }
                                        
                                        if (isset($params["dm_setting_style"])) {
                                            $pageTitle .= " " . $params["dm_setting_style"]["label"];
                                        }
                                        
                                        if (isset($params["dm_stone_type"])) {
                                            if ($params["dm_stone_type"]["label"] == "Setting") {
                                                $pageTitle .= " Engagement Ring Settings";
                                            } else {
                                                if ($params["dm_stone_type"]["label"] != "Diamond") {
                                                    $pageTitle .= " " . $params["dm_stone_type"]["label"];
                                                }
                                                
                                                $pageTitle .= " Diamond";
                                            }
                                        }
                                        
                                        if (!isset($params["dm_stone_type"]) || $params["dm_stone_type"]["label"] != "Setting") {
                                            if (isset($params["dm_band"]) && $params["dm_band"]["label"] != "No Band") {
                                                $pageTitle .= " Bridal Wedding Ring Set";
                                            } else {
                                                $pageTitle .= " Engagement Rings";
                                            }
                                        }
                                        
                                        if (isset($params["dm_metal"])) {
                                            $pageTitle .= " - " . $params["dm_metal"]["label"];
                                        }
                                        
                                        $pageTitle = trim($pageTitle);

                                        $cond = array(
                                            array(
                                                "filter"=>$code,
                                                "value"=>array($value)
                                            ),
                                            array(
                                                "filter"=>$code2,
                                                "value"=>array($value2)
                                            ),
                                            array(
                                                "filter"=>$code3,
                                                "value"=>array($value3)
                                            ),
                                            array(
                                                "filter"=>$code4,
                                                "value"=>array($value4)
                                            ),
                                        );
                                        
                                        $query = "INSERT INTO amasty_amshopby_page (`position`, `title`, `description`, `meta_title`, `meta_keywords`, `meta_description`, `conditions`, `categories`) VALUES ('replace', '" . $pageTitle . "', '".$pageTitle."', '".$pageTitle."', '', '".$pageTitle."', '".serialize($cond)."', 26)";
                                        $this->_connection->query($query);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}