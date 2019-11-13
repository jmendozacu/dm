<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_Fpc
 */


namespace Amasty\Fpc\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
    /**
     * @var Operation\CreateActivityTable
     */
    private $createActivityTable;

    /**
     * @var Operation\CreateFlushPagesTable
     */
    private $createFlushPagesTable;

    /**
     * @var Operation\CreateReportsTable
     */
    private $createReportsTable;


    public function __construct(
        Operation\CreateFlushPagesTable $createFlushPagesTable,
        Operation\CreateActivityTable $createActivityTable,
        Operation\CreateReportsTable $createReportsTable
    ) {
        $this->createFlushPagesTable = $createFlushPagesTable;
        $this->createActivityTable = $createActivityTable;
        $this->createReportsTable = $createReportsTable;
    }

    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        
        if (version_compare($context->getVersion(), '2.0.0', '<')) {
            $this->createActivityTable->execute($setup);
        }

        if (!$context->getVersion() || version_compare($context->getVersion(), '2.0.0', '<')) {
            $this->createFlushPagesTable->execute($setup);
        }

        if (!$context->getVersion() || version_compare($context->getVersion(), '2.0.1', '<')) {
            $this->createReportsTable->execute($setup);
            $this->addMobileColumn($setup);
        }

        $setup->endSetup();
    }

    private function addMobileColumn(SchemaSetupInterface $setup)
    {
        $setup->getConnection()->addColumn(
            $setup->getTable(Operation\CreateLogTable::TABLE_NAME),
            'mobile',
            Table::TYPE_BOOLEAN,
            null,
            ['nullable' => false, 'default' => 0],
            'Mobile'
        );
    }
}
