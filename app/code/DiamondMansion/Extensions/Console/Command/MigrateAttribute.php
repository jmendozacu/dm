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

        $query = "SELECT c.* FROM catalog_product_entity_" . $type . " as c LEFT JOIN eav_attribute AS e ON c.attribute_id=e.attribute_id WHERE c.`value` IS NOT NULL AND e.attribute_code='" . $src . "'";
        $result = $m1Connect->query($query);

        $count = 0;
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

        $m1Connect->close();

        $output->writeln('<info>Processed ' . $count . ' values.</info>');

        return 0;
    }
}