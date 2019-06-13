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

class MigrateAttribute extends Command
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

        $this->setName('dm:migrate:attribute')
            ->setDescription('Migrate attribute...')
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
        $type = $input->getOption('type');
        $src = $input->getOption('src');
        $dst = $input->getOption('dst');

        $m1Connect = new \mysqli($host, $user, $pass, $db) or die ('Connect failed to magento 1 database');

        $count = 0;
        if ($type == 'side-stone') {
            $srcIds = [
                [247, 242, 252],
                [248, 243, 253],
                [249, 244, 254],
                [250, 245, 255],
            ];
            $counter = 0;
            foreach ($srcIds as $srcId) {
                $counter ++;
                $query = "SELECT i.entity_id, v.`value` FROM catalog_product_entity_int AS i LEFT JOIN eav_attribute_option_value AS v ON i.`value`=v.`option_id` WHERE i.attribute_id=" . $srcId[0] . " AND i.`value` IS NOT NULL";
                $result = $m1Connect->query($query);
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $query = "SELECT i.`value` FROM catalog_product_entity_varchar AS i WHERE i.attribute_id=" . $srcId[1] . " AND i.entity_id=" . $row['entity_id'] . " AND (i.`value` IS NOT NULL OR i.`value` != 0)";
                        $caratResult1 = $m1Connect->query($query);
                        $carat1 = 0;
                        if ($caratResult1->num_rows > 0) {
                            while ($caratRow1 = $caratResult1->fetch_assoc()) {
                                $carat1 = floatval($caratRow1['value']);
                                break;
                            }
                        }

                        $query = "SELECT i.`value` FROM catalog_product_entity_varchar AS i WHERE i.attribute_id=" . $srcId[2] . " AND i.entity_id=" . $row['entity_id'] . " AND (i.`value` IS NOT NULL OR i.`value` != 0)";
                        $caratResult2 = $m1Connect->query($query);
                        $carat2 = 0;
                        if ($caratResult2->num_rows > 0) {
                            while ($caratRow2 = $caratResult2->fetch_assoc()) {
                                $carat2 = floatval($caratRow2['value']);
                                break;
                            }
                        }

                        $sku = strtolower(substr($row['value'], 0, 1));
                        if ($row['value'] == 'Princess') {
                            $sku = 'q';
                        } else if ($row['value'] == 'Radiant') {
                            $sku = 'i';
                        } else if ($row['value'] == 'Trapezoid') {
                            $sku = 'g';
                        } else if ($row['value'] == 'Halfmoon') {
                            $sku = 'f';
                        } else if ($row['value'] == 'Bullet') {
                            $sku = 'd';
                        }

                        if ($carat1 > 0 || $carat2 > 0) {
                            $query = "DELETE FROM dm_product_options WHERE `group` LIKE 'side-stone-%' AND product_id=" . $row['entity_id'];
                            $this->_connection->query($query);

                            try {
                                $query = "INSERT INTO dm_product_options (`product_id`, `group`, `code`, `title`, `slug`, `is_default`, `values`) VALUES (" . $row['entity_id'] . ", 'side-stone-shape-" . $counter . "', '" . strtolower($row['value']) . "', '" . $row['value'] . "', '" . $sku . "', 1, '" . json_encode(['qty'=>[$carat1/0.01, $carat2/0.01]]) . "')";
                                $this->_connection->query($query);
                                $query = "INSERT INTO dm_product_options (`product_id`, `group`, `code`, `title`, `slug`, `is_default`, `values`) VALUES (" . $row['entity_id'] . ", 'side-stone-carat-" . $counter . "', '0.01', '0.01', 'b', 1, '')";
                                $this->_connection->query($query);
                                $query = "INSERT INTO dm_product_options (`product_id`, `group`, `code`, `title`, `slug`, `is_default`, `values`) VALUES (" . $row['entity_id'] . ", 'side-stone-color-clarity-" . $counter . "', 'f-g/vs', 'F-G/VS', 'f', 1, '')";
                                $this->_connection->query($query);
                                $count ++;
                            } catch (\Exception $e) {
                                $output->writeln('<error>Product ' . $row['entity_id'] . ' Error.</error>');
                            }
                        }
                    }
                }
            }
        } else if ($type == 'multiple') {
            $query = "SELECT a.attribute_id, o.`option_id`, v.`value` FROM eav_attribute AS a LEFT JOIN eav_attribute_option AS o ON a.`attribute_id`=o.`attribute_id` LEFT JOIN eav_attribute_option_value AS v ON o.`option_id`=v.`option_id` WHERE a.`attribute_code`='" . $src . "';";
            $result = $m1Connect->query($query);
            $srcAttributeId = 0;
            $srcAttributeOptions = [];
            while ($row = $result->fetch_assoc()) {
                $srcAttributeId = $row['attribute_id'];
                $srcAttributeOptions[$row['option_id']] = $row['value'];
            }

            $query = "SELECT a.attribute_id, o.`option_id`, v.`value` FROM eav_attribute AS a LEFT JOIN eav_attribute_option AS o ON a.`attribute_id`=o.`attribute_id` LEFT JOIN eav_attribute_option_value AS v ON o.`option_id`=v.`option_id` WHERE a.`attribute_code`='" . $dst . "';";
            $rows = $this->_connection->fetchAll($query);
            $dstAttributeId = 0;
            $dstAttributeOptions = [];
            foreach ($rows as $row) {
                $dstAttributeId = $row['attribute_id'];
                $dstAttributeOptions[$row['value']] = $row['option_id'];
            }

            $query = "SELECT c.* FROM catalog_product_entity_varchar as c LEFT JOIN eav_attribute AS e ON c.attribute_id=e.attribute_id WHERE c.`value` IS NOT NULL AND e.attribute_code='" . $src . "'";
            $result = $m1Connect->query($query);

            if ($result->num_rows > 0) {
                $query = "DELETE FROM catalog_product_entity_varchar WHERE attribute_id=" . $dstAttributeId;
                $this->_connection->query($query);
    
                while ($row = $result->fetch_assoc()) {
                    try {
                        $srcValues = explode(',', $row['value']);
                        $dstValues = [];
                        foreach ($srcValues as $srcValue) {
                            if ($dst == 'dm_stone_type' && $srcAttributeOptions[$srcValue] == 'Diamond') {
                                $dstValues[] = $dstAttributeOptions['Natural'];
                            } else {
                                $dstValues[] = $dstAttributeOptions[$srcAttributeOptions[$srcValue]];
                            }
                        }

                        $dstValues = array_unique($dstValues);

                        $query = "INSERT INTO catalog_product_entity_varchar (`attribute_id`, `store_id`, `entity_id`, `value`) VALUES (" . $dstAttributeId . ", 0, " . $row['entity_id'] . ", '" . implode(',', $dstValues) . "')";
                        $this->_connection->query($query);
                        $count ++;
                    } catch (\Exception $e) {
                        $output->writeln('<error>Product ' . $row['entity_id'] . ' Error.</error>');
                    }
                }
            }
        } else {
            $query = "SELECT c.* FROM catalog_product_entity_" . $type . " as c LEFT JOIN eav_attribute AS e ON c.attribute_id=e.attribute_id WHERE c.`value` IS NOT NULL AND e.attribute_code='" . $src . "'";
            $result = $m1Connect->query($query);

            if ($result->num_rows > 0) {
                $rows = $this->_connection->fetchAll("SELECT attribute_id FROM eav_attribute WHERE attribute_code='" . $dst . "'");
                foreach ($rows as $row) {
                    $dstId = $row['attribute_id'];
                    
                    $query = "DELETE FROM catalog_product_entity_" . $type . " WHERE attribute_id=" . $dstId;
                    $this->_connection->query($query);
        
                    while ($row = $result->fetch_assoc()) {
                        try {
                            $query = "INSERT INTO catalog_product_entity_" . $type . " (`attribute_id`, `store_id`, `entity_id`, `value`) VALUES (" . $dstId . ", 0, " . $row['entity_id'] . ", '" . $row['value'] . "')";
                            $this->_connection->query($query);
                            $count ++;
                        } catch (\Exception $e) {
                            $output->writeln('<error>Product ' . $row['entity_id'] . ' Error.</error>');
                        }
                    }

                    break;
                }
            }
        }

        $m1Connect->close();

        $output->writeln('<info>Processed ' . $count . ' values.</info>');

        return 0;
    }
}