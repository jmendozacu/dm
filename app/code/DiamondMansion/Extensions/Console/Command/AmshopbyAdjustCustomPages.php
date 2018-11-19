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

class AmshopbyAdjustCustomPages extends Command
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
        $this->setName('dm:amshopby:adjustcustompages')
            ->setDescription('Adjust Amshopby Custom Pages...');

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

        $this->adjustEngagementRingCustomPages($output);

        $output->writeln('<info>Finished.</info>');

        return 0;
    }

    public function adjustEngagementRingCustomPages($output) {

        $mainAttributeCodes = array("dm_stone_type", "dm_stone_shape", "dm_metal", "dm_band", "dm_setting_style", "dm_design_collection");
        
        $mainAttributeIds = array();
        foreach ($mainAttributeCodes as $attribueCode) {
            $attr = $this->_eavConfig->getAttribute('catalog_product', $attribueCode);
            $mainAttributeIds[$attribueCode] = $attr->getId();
        }

        $rows = $this->_connection->fetchAll("SELECT * from amasty_amshopby_page WHERE categories = 26 AND conditions LIKE '%dm_%'");
        foreach ($rows as $row) {
            $conditions = unserialize($row['conditions']);
            foreach ($conditions as &$condition) {
                if (isset($condition['filter'])) {
                    $condition['filter'] = $mainAttributeIds[$condition['filter']];
                }
                $condition = json_encode($condition);
            }
            $this->_connection->query("UPDATE amasty_amshopby_page SET conditions='" . serialize($conditions) . "' WHERE page_id = " . $row['page_id']);
        }
    }
}