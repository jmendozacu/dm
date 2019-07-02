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
use Symfony\Component\Console\Input\InputOption;

class AdjustMedia extends Command
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
        $options = [
            new InputOption("h", null, InputOption::VALUE_REQUIRED, 'Host'),
            new InputOption("u", null, InputOption::VALUE_REQUIRED, 'User'),
            new InputOption("p", null, InputOption::VALUE_REQUIRED, 'Password'),
            new InputOption("db", null, InputOption::VALUE_REQUIRED, 'DB Name'),
        ];

        $this->setName('dm:adjust:media')
            ->setDescription('Adjust media...')
            ->setDefinition($options);

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

        $host = $input->getOption('h');
        $user = $input->getOption('u');
        $pass = $input->getOption('p');
        $db = $input->getOption('db');

        $attributeIds = [];
        $rows = $this->_connection->fetchAll("SELECT attribute_code, attribute_id FROM eav_attribute WHERE attribute_code='image' OR attribute_code='small_image' OR attribute_code='thumbnail'");
        foreach ($rows as $row) {
            $attributeIds[$row['attribute_code']] = $row['attribute_id'];
        }

        if (count($attributeIds) != 3) {
            $output->writeln('<info>Could not be found media attributes.</info>');
            return -1;
        }

        $mediaVars = [];

        $query = "SELECT * FROM catalog_product_entity_varchar WHERE FIND_IN_SET(attribute_id, '" . implode(',', $attributeIds) . "')";
        $rows = $this->_connection->fetchAll($query);
        foreach ($rows as $row) {
            if (!isset($mediaVars[$row['entity_id']])) {
                $mediaVars[$row['entity_id']] = [];
            }

            $mediaVars[$row['entity_id']][$row['attribute_id']] = $row['value'];
        }

        foreach ($mediaVars as $entityId => $mediaEntityVars) {
            if (count($mediaEntityVars) >= 3) {
                continue;
            }

            $output->writeln('<info>Processed ' . $entityId . '.</info>');

            $mediaEntityVar = current($mediaEntityVars);
            foreach ($attributeIds as $attributeId) {
                if (!isset($mediaEntityVars[$attributeId])) {
                    try {
                        $query = "INSERT INTO catalog_product_entity_varchar (`attribute_id`, `store_id`, `entity_id`, `value`) VALUES (" . $attributeId . ", 0, " . $entityId . ", '" . $mediaEntityVar . "')";
                        $this->_connection->query($query);
                    } catch (\Exception $e) {
                        $output->writeln('<error>Product ' . $entityId . ' Error.</error>');
                    }
                }
            }
        }

        $output->writeln('<info>Processed.</info>');

        return 0;
    }
}