<?php

namespace DiamondMansion\Extensions\Setup;

use \Magento\Framework\Setup\InstallSchemaInterface;
use \Magento\Framework\Setup\ModuleContextInterface;
use \Magento\Framework\Setup\SchemaSetupInterface;
use \Magento\Framework\DB\Ddl\Table;

/**
 * Class InstallSchema
 *
 * @package DiamondMansion\Extensions\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * Install Designer table
     *
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $this->createDesignRingStonePriceEntityTable($setup);
        $this->createDesignRingSidestonePriceEntityTable($setup);
        $this->createEternityRingStonePriceEntityTable($setup);

        $setup->endSetup();
    }

    private function createDesignRingStonePriceEntityTable($setup) {
        $tableName = $setup->getTable('dm_design_ring_stone_price_entity');

        if ($setup->getConnection()->isTableExists($tableName) != true) {
            $table = $setup->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'entity_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'ID'
                )
                ->addColumn(
                    'shape',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Shape'
                )
                ->addColumn(
                    'carat',
                    Table::TYPE_DECIMAL,
                    '10,2',
                    ['nullable' => false],
                    'Carat'
                )
                ->addColumn(
                    'color',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Color'
                )
                ->addColumn(
                    'clarity',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Clarity'
                )
                ->addColumn(
                    'price',
                    Table::TYPE_DECIMAL,
                    '12,4',
                    ['nullable' => false],
                    'Price'
                )
                ->setComment('Design Ring - Stone Price');
            $setup->getConnection()->createTable($table);
        }
    }
    private function createDesignRingSidestonePriceEntityTable($setup) {
        $tableName = $setup->getTable('dm_design_ring_sidestone_price_entity');

        if ($setup->getConnection()->isTableExists($tableName) != true) {
            $table = $setup->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'entity_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'ID'
                )
                ->addColumn(
                    'shape',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Shape'
                )
                ->addColumn(
                    'carat',
                    Table::TYPE_DECIMAL,
                    '10,3',
                    ['nullable' => false],
                    'Carat'
                )
                ->addColumn(
                    'color_clarity',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Color'
                )
                ->addColumn(
                    'price',
                    Table::TYPE_DECIMAL,
                    '12,4',
                    ['nullable' => false],
                    'Price'
                )
                ->setComment('Design Ring - Sidestone Price');
            $setup->getConnection()->createTable($table);
        }
    }
    private function createEternityRingStonePriceEntityTable($setup) {
        $tableName = $setup->getTable('dm_eternity_ring_stone_price_entity');

        if ($setup->getConnection()->isTableExists($tableName) != true) {
            $table = $setup->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'entity_id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'ID'
                )
                ->addColumn(
                    'shape',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Shape'
                )
                ->addColumn(
                    'carat',
                    Table::TYPE_DECIMAL,
                    '10,3',
                    ['nullable' => false],
                    'Carat'
                )
                ->addColumn(
                    'color_clarity',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Color'
                )
                ->addColumn(
                    'price',
                    Table::TYPE_DECIMAL,
                    '12,4',
                    ['nullable' => false],
                    'Price'
                )
                ->setComment('Design Ring - Sidestone Price');
            $setup->getConnection()->createTable($table);
        }
    }
}