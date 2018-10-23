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

class AddRingSizeOptions extends Command
{
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */

    private $state;

    protected $_objectManager;
    protected $_connection;
    protected $_eavConfig;
    protected $_productCollectionFactory;

    public function __construct(
        ObjectManagerFactory $objectManagerFactory,
        ResourceConnection $resource,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Framework\App\State $state
    ) {
        $params = $_SERVER;
        $params[StoreManager::PARAM_RUN_CODE] = 'admin';
        $params[StoreManager::PARAM_RUN_TYPE] = 'store';

        $this->_objectManager = $objectManagerFactory->create($params);
        $this->_connection = $resource->getConnection();
        $this->_eavConfig = $eavConfig;
        $this->_productCollectionFactory = $productCollectionFactory;

        $this->state = $state;

        parent::__construct();
    }   
    
    protected function configure()
    {
        $this->setName('dm:addoptions:ringsize')
            ->setDescription('Generate Ring Size Custom Options...');

        parent::configure();
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->state->setAreaCode('adminhtml');

        $output->writeln('<info>Starting...</info>');

        try {
            $collection = $this->_productCollectionFactory->create()
            ->addFieldToFilter('type_id', 'configurable')
            ->addAttributeToFilter([['attribute' => 'name', 'like' => '%ring%'], ['attribute' => 'name', 'like' => '%band%']]);

            foreach ($collection as $product) {
                //$output->writeln($item->getId());
                //$product = $this->_objectManager->create('\Magento\Catalog\Model\Product')->load($item->getId());

                //break;
                $values = [
                    ['record_id' => 0, 'title' => '3', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 1, 'is_delete' => 0],
                    ['record_id' => 1, 'title' => '3.25', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 2, 'is_delete' => 0],
                    ['record_id' => 2, 'title' => '3.5', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 3, 'is_delete' => 0],
                    ['record_id' => 3, 'title' => '3.75', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 4, 'is_delete' => 0],
                    ['record_id' => 4, 'title' => '4', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 5, 'is_delete' => 0],
                    ['record_id' => 5, 'title' => '4.25', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 6, 'is_delete' => 0],
                    ['record_id' => 6, 'title' => '4.5', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 7, 'is_delete' => 0],
                    ['record_id' => 7, 'title' => '4.75', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 8, 'is_delete' => 0],
                    ['record_id' => 8, 'title' => '5', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 9, 'is_delete' => 0],
                    ['record_id' => 9, 'title' => '5.25', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 10, 'is_delete' => 0],
                    ['record_id' => 10, 'title' => '5.5', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 11, 'is_delete' => 0],
                    ['record_id' => 11, 'title' => '5.75', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 12, 'is_delete' => 0],
                    ['record_id' => 12, 'title' => '6', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 13, 'is_delete' => 0],
                    ['record_id' => 13, 'title' => '6.25', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 14, 'is_delete' => 0],
                    ['record_id' => 14, 'title' => '6.5', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 15, 'is_delete' => 0],
                    ['record_id' => 15, 'title' => '6.75', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 16, 'is_delete' => 0],
                    ['record_id' => 16, 'title' => '7', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 17, 'is_delete' => 0],
                    ['record_id' => 17, 'title' => '7.25', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 18, 'is_delete' => 0],
                    ['record_id' => 18, 'title' => '7.5', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 19, 'is_delete' => 0],
                    ['record_id' => 19, 'title' => '7.75', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 20, 'is_delete' => 0],
                    ['record_id' => 20, 'title' => '8', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 21, 'is_delete' => 0],
                    ['record_id' => 21, 'title' => '8.25', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 22, 'is_delete' => 0],
                    ['record_id' => 22, 'title' => '8.5', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 23, 'is_delete' => 0],
                    ['record_id' => 23, 'title' => '8.75', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 24, 'is_delete' => 0],
                    ['record_id' => 24, 'title' => '9', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 25, 'is_delete' => 0],
                    ['record_id' => 25, 'title' => '9.25', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 26, 'is_delete' => 0],
                    ['record_id' => 26, 'title' => '9.5', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 27, 'is_delete' => 0],
                    ['record_id' => 27, 'title' => '9.75', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 28, 'is_delete' => 0],
                    ['record_id' => 28, 'title' => '10', 'price' => '', 'price_type' => 'fixed', 'sort_order' => 29, 'is_delete' => 0],
                ];

                $option = [
                    'sort_order' => 1,
                    'title' => 'Ring Size',
                    'price_type' => 'fixed',
                    'price' => '',
                    'type' => 'drop_down',
                    'is_require' => 1,
                    'values' => $values
                ];

                $product->setHasOptions(1);
                $product->setCanSaveCustomOptions(true);
                $option = $this->_objectManager->create('\Magento\Catalog\Model\Product\Option')
                    ->setProductId($product->getId())
                    ->setStoreId($product->getStoreId())
                    ->addData($option);
                $option->save();
                $product->addOption($option);

                $output->writeln('SKU: ' . $product->getSku());
            }

        } catch (Exception $e) {
            $output->writeln($e->getMessage());
        }

        
        $output->writeln('<info>Finished.</info>');

        return 0;
    }
}