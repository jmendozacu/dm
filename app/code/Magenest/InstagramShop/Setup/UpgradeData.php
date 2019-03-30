<?php
/**
 *
  * Copyright © 2018 Magenest. All rights reserved.
  * See COPYING.txt for license details.
  *
  * Magenest_InstagramShop extension
  * NOTICE OF LICENSE
  *
  * @category Magenest
  * @package  Magenest_InstagramShop
  * @author    dangnh@magenest.com

 */

namespace Magenest\InstagramShop\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Catalog\Model\Product;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class UpgradeData implements UpgradeDataInterface
{

    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;

    /**
     * InstallData constructor.
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        EavSetupFactory $eavSetupFactory
    )
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        if (version_compare($context->getVersion(), '2.0.1') < 0) {
            $setup->startSetup();
            $this->addInstagramPhotos($eavSetup);
            $setup->endSetup();
        }
    }

    private function addInstagramPhotos($eavSetup)
    {
        $eavSetup->addAttribute(
            Product::ENTITY,
            'instagram_photos',
            [
                'group' => 'General',
                'type' => 'text',
                'visible' => 0,
                'required' => 0,
                'input' => 'text',
                'user_defined' => 0,
                'visible_on_front' => 0,
                'global' => \Magento\Catalog\Model\ResourceModel\Eav\Attribute::SCOPE_GLOBAL,
                'label' => 'Instagram Photos',
                'system' => 0,
                'is_used_in_grid' => 0,
                'is_visible_in_grid' => 0,
                'is_filterable_in_grid' => 0
            ]
        );
    }
}