<?php
/**
 * Regenerate Url rewrites
 */

namespace DiamondMansion\Extensions\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Magento\Catalog\Model\Product\Visibility;

class RegenerateUrlRewrites extends RegenerateUrlRewritesAbstract
{
    /**
     * @var null|Symfony\Component\Console\Output\OutputInterface
     */
    protected $_output = null;

    /**
     * Regenerate Url Rewrites
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        set_time_limit(0);
        $this->_output = $output;
        /*
        $products = $this->_productCollectionFactory->create()->addAttributeToSelect('*');
        foreach ($products as $product) {
            if ($product->getVisibility() == Visibility::VISIBILITY_NOT_VISIBLE) {
                continue;
            }

            try {
                $sql = "REPLACE INTO {$this->_resource->getTableName('url_rewrite')} (entity_type, entity_id, request_path, target_path, redirect_type, store_id, `description`, is_autogenerated, metadata) VALUES ('product', '{$product->getId()}', '{$product->getUrlKey()}/', 'catalog/product/view/id/{$product->getId()}', 0, 1, NULL, 1, NULL);";
                $this->_resource->getConnection()->query($sql);
            } catch (Exception $e) {
                $this->_output->writeln($product->getUrlKey());
            }            
        }
        return;
        */

        $allStores = $this->getAllStoreIds();
        $storesList = $productsFilter = [];

        $this->_output->writeln('Regenerating of URL rewrites:');
        $this->_showSupportMe();

        $options = $input->getOptions();
        if (isset($options[self::INPUT_KEY_SAVE_REWRITES_HISTORY]) && $options[self::INPUT_KEY_SAVE_REWRITES_HISTORY] === true) {
            $this->_saveOldUrls = true;
        }

        if (isset($options[self::INPUT_KEY_NO_REINDEX]) && $options[self::INPUT_KEY_NO_REINDEX] === true) {
            $this->_runReindex = false;
        }

        if (isset($options[self::INPUT_KEY_PRODUCTS_RANGE])) {
            $productsFilter = $this->generateProductsIdsRange($options[self::INPUT_KEY_PRODUCTS_RANGE]);
        }

        // get store Id (if was set)
        $storeId = $input->getArgument(self::INPUT_KEY_STOREID);
        if (is_null($storeId)) {
            $storeId = $input->getOption(self::INPUT_KEY_STOREID);
        }

        // if store ID is not specified the re-generate for all stores
        if (is_null($storeId)) {
            $storesList = $allStores;
        }
        // we will re-generate URL only in this specific store (if it exists)
        elseif (strlen($storeId) && ctype_digit($storeId)) {
            if (isset($allStores[$storeId])) {
                $storesList = array(
                    $storeId => $allStores[$storeId]
                );
            } else {
                $this->displayError('ERROR: store with this ID not exists.');
                return;
            }
        }
        // disaply error if user set some incorrect value
        else {
            $this->displayError('ERROR: store ID should have a integer value.', true);
            return;
        }

        // remove all current url rewrites
        if (count($storesList) > 0 && !$this->_saveOldUrls) {
            $this->removeAllUrlRewrites($storesList, $productsFilter);
        }

        // set area code if needed
        try {
            $areaCode = $this->_appState->getAreaCode();
        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            // if area code is not set then magento generate exception "LocalizedException"
            $this->_appState->setAreaCode('adminhtml');
        }

        foreach ($storesList as $storeId => $storeCode) {
            $this->_output->writeln('');
            $this->_output->writeln("[Store ID: {$storeId}, Store View code: {$storeCode}]:");

            if (count($productsFilter) > 0) {
                $this->regenerateProductsRangeUrlRewrites($productsFilter, $storeId);
            } else {
                $this->regenerateAllUrlRewrites($storeId);
            }
        }

        $this->_output->writeln('');
        $this->_output->writeln('');

        if ($this->_runReindex == true) {
            $this->_output->writeln('Reindexation...');
            shell_exec('php bin/magento indexer:reindex');
        }

        $this->_output->writeln('Cache refreshing...');
        shell_exec('php bin/magento cache:clean');
        shell_exec('php bin/magento cache:flush');
        $this->_output->writeln('If you use some external cache mechanisms (e.g.: Redis, Varnish, etc.) - please, refresh this external cache.');
        $this->_output->writeln('Finished');
    }

    /**
     * @see parent::regenerateAllUrlRewrites()
     */
    public function regenerateAllUrlRewrites($storeId = 0)
    {
        $this->_step = 0;

        // get categories collection
        $categories = $this->_categoryCollectionFactory->create()
            ->addAttributeToSelect('*')
            ->setStore($storeId)
            ->addAttributeToFilter('is_active','1')
            ->addFieldToFilter('level', array('gt' => '1'))
            ->setOrder('level', 'DESC');

        foreach ($categories as $category) {
            $this->_regenerateCategoryUrlRewrites($category);

            try {
                //frees memory for maps that are self-initialized in multiple classes that were called by the generators
                $this->resetUrlRewritesDataMaps($category);

                $this->_displayProgressDots();
            } catch (\Exception $e) {
                // debugging
                $this->_displayExceptionMsg('Exception #3: '. $e->getMessage() .' Category ID: '. $category->getId());
            }
        }
    }

    /**
     * Display error message
     * @param  string  $errorMsg
     * @param  boolean $displayHint
     * @return void
     */
    private function displayError($errorMsg, $displayHint = false)
    {
        $this->_output->writeln('');
        $this->_output->writeln($errorMsg);

        if ($displayHint) {
            $this->_output->writeln('Correct command is: bin/magento dm:urlrewrites:regenerate 19');
        }

        $this->_output->writeln('Finished');
    }
}