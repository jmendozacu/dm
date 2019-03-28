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

class AddCutOptions extends Command
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
        $this->setName('dm:addoptions:cut')
            ->setDescription('Generate Cut & Brilliance Options...');

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

        $this->addCutOptionsDesigRing($output);

        $this->assignCutOptionsToType($output);

        $output->writeln('<info>Finished.</info>');

        return 0;
    }

    public function addCutOptionsDesigRing($output) {
        $rows = $this->_connection->fetchAll("SELECT DISTINCT product_id from dm_product_options WHERE `group` = 'main-stone-type' AND product_id NOT IN (SELECT product_id from dm_product_options WHERE `group` = 'main-stone-cut')");
        foreach ($rows as $row) {
            $productId = $row['product_id'];

            $query = "INSERT INTO dm_product_options (`product_id`, `group`, `code`, `title`, `slug`, `is_default`) VALUES (" . $productId . ", 'main-stone-cut', 'ideal-10', 'Ideal 10', 'a', 1)";
            $this->_connection->query($query);
            $query = "INSERT INTO dm_product_options (`product_id`, `group`, `code`, `title`, `slug`, `is_default`) VALUES (" . $productId . ", 'main-stone-cut', 'excellent', 'Excellent', 'b', 0)";
            $this->_connection->query($query);
            $query = "INSERT INTO dm_product_options (`product_id`, `group`, `code`, `title`, `slug`, `is_default`) VALUES (" . $productId . ", 'main-stone-cut', 'very-good', 'Very Good', 'c', 0)";
            $this->_connection->query($query);    
        }
    }

    public function assignCutOptionsToType($output) {
        $rows = $this->_connection->fetchAll("SELECT * from dm_product_options WHERE `group` = 'main-stone-type' AND code <> 'setting' AND product_id IN (SELECT product_id from dm_product_options WHERE `group` = 'main-stone-cut')");
        foreach ($rows as $row) {
            $productId = $row['product_id'];

            $values = json_decode($row['values'], true);
            if (!isset($values['children'])) {
                continue;
            }

            $values['children']['main-stone-cut'] = [
                'ideal-10',
                'excellent',
                'very-good'
            ];

            $query = "UPDATE dm_product_options SET `values`='" . json_encode($values) . "' WHERE `entity_id`=" . $row['entity_id'];
            $this->_connection->query($query);
        }
    }
}