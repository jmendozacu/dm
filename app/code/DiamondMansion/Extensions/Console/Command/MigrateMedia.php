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

class MigrateMedia extends Command
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
            new InputOption("type", null, InputOption::VALUE_REQUIRED, 'Attribute Type'),
            new InputOption("src", null, InputOption::VALUE_REQUIRED, 'Src Attribute Code'),
            new InputOption("dst", null, InputOption::VALUE_REQUIRED, 'Dst Attribute Code')
        ];

        $this->setName('dm:migrate:media')
            ->setDescription('Migrate media...')
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

        $attributeId = 0;
        $rows = $this->_connection->fetchAll("SELECT attribute_id FROM eav_attribute WHERE attribute_code='media_gallery'");
        foreach ($rows as $row) {
            $attributeId = $row['attribute_id'];
            break;
        }

        if (!$attributeId) {
            $output->writeln('<info>Could not be found media_gallery attribute id.</info>');
            return -1;
        }

        $m1Connect = new \mysqli($host, $user, $pass, $db) or die ('Connect failed to magento 1 database');

        $query = "SELECT * FROM catalog_product_entity_media_gallery";
        $result = $m1Connect->query($query);

        $entityIds = [];

        if ($result->num_rows > 0) {
            $query = "DELETE FROM catalog_product_entity_media_gallery WHERE media_type='image'";
            $this->_connection->query($query);

            while ($row = $result->fetch_assoc()) {
                $entityIds[$row['value_id']] = $row['entity_id'];
                try {
                    $query = "INSERT INTO catalog_product_entity_media_gallery (`value_id`, `attribute_id`, `value`, `media_type`, `disabled`) VALUES (" . $row['value_id'] . ", " . $attributeId . ", '" . $row['value'] . "', 'image', 0)";
                    $this->_connection->query($query);
                } catch (\Exception $e) {
                    $output->writeln('<error>Product ' . $row['entity_id'] . ' Error.</error>');
                }
            }
        }

        $query = "SELECT * FROM catalog_product_entity_media_gallery_value";
        $result = $m1Connect->query($query);

        if ($result->num_rows > 0) {
            $query = "DELETE FROM catalog_product_entity_media_gallery_value WHERE value_id IN (SELECT value_id FROM catalog_product_entity_media_gallery WHERE media_type='image')";
            $this->_connection->query($query);

            while ($row = $result->fetch_assoc()) {
                if (!isset($entityIds[$row['value_id']])) {
                    continue;
                }

                try {
                    $query = "INSERT INTO catalog_product_entity_media_gallery_value (`value_id`, `store_id`, `entity_id`, `label`, `position`, `disabled`) VALUES (" . $row['value_id'] . ", " . $row['store_id'] . ", '" . $entityIds[$row['value_id']] . "', '" . $row['label'] . "', " . $row['position'] . ", " . $row['disabled'] . ")";
                    $this->_connection->query($query);
                } catch (\Exception $e) {
                    $output->writeln('<error>Product ' . $entityIds[$row['value_id']] . ' Error.</error>');
                }
            }
        }

        $query = "DELETE FROM catalog_product_entity_media_gallery_value_to_entity WHERE value_id IN (SELECT value_id FROM catalog_product_entity_media_gallery WHERE media_type='image')";
        $this->_connection->query($query);

        foreach ($entityIds as $valueId => $entityId) {
            try {
                $query = "INSERT INTO catalog_product_entity_media_gallery_value_to_entity (`value_id`, `entity_id`) VALUES (" . $valueId . ", " . $entityId . ")";
                $this->_connection->query($query);
            } catch (\Exception $e) {
                $output->writeln('<error>Product ' . $entityId . ' Error.</error>');
            }
        }

        $m1Connect->close();

        $output->writeln('<info>Processed.</info>');

        return 0;
    }
}