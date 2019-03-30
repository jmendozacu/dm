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

use Magenest\InstagramShop\Model\Photo;
use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;

/**
 * Class UpgradeSchema
 * @package Magenest\InstagramShop\Setup
 */
class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        $this->createPhotoTable($installer);
        $this->createTaggedTable($installer);

        if (version_compare($context->getVersion(), '2.0.2') < 0) {
            $this->addShowInWidgetColumn($installer);
        }
        if (version_compare($context->getVersion(), '2.0.3') < 0) {
            $this->addCreatedAtColumn($installer);
        }
        if (version_compare($context->getVersion(), '4.0.0') < 0) {
            $this->createHotspotTable($installer);
        }
        if (version_compare($context->getVersion(), '4.1.0') < 0) {
            $this->createReportTable($installer);
        }
        if (version_compare($context->getVersion(), '4.1.1') < 0) {
            $this->modifyProductDataColumn($installer);
        }
        if (version_compare($context->getVersion(), '4.1.2') < 0) {
            $this->addUpdateColumn($installer);
        }
        if (version_compare($context->getVersion(), '4.1.3') < 0) {
            $this->addPositionColumn($installer);
        }
        if (version_compare($context->getVersion(), '4.3.0') < 0) {
            $this->createReportLogTable($installer);
        }
        if (version_compare($context->getVersion(), '4.3.1') < 0) {
            $this->modifyReportLogColumns($installer);
        }
        if (version_compare($context->getVersion(), '4.3.2') < 0) {
            $this->addVideoSourceTypeColumn($installer);
        }
        $installer->endSetup();
    }

    /**
     * @param SchemaSetupInterface $setup
     */
    private function addVideoSourceTypeColumn(SchemaSetupInterface $setup)
    {
        $tableName = $setup->getTable('magenest_instagram_photo');
        $setup->getConnection()->addColumn($tableName, 'video_source', [
            'type' => Table::TYPE_TEXT,
            'size' => '64k',
            'nullable' => true,
            'comment' => 'Video Source URL'
        ]);
    }

    /**
     * @param SchemaSetupInterface $setup
     */
    private function modifyReportLogColumns(SchemaSetupInterface $setup)
    {
        $tableName = $setup->getTable('magenest_instagramshop_reportlog');
        $columns   = ['clicked_at', 'added_at', 'ordered_at'];
        foreach ($columns as $column) {
            $setup->getConnection()->modifyColumn($tableName, $column, ['type' => Table::TYPE_DATETIME, 'size' => null, 'nullable' => true, 'default' => null]);
        }
    }

    /**
     * @param SchemaSetupInterface $setup
     * @throws \Zend_Db_Exception
     */
    private function createReportLogTable(SchemaSetupInterface $setup)
    {
        $tableName = $setup->getTable('magenest_instagramshop_reportlog');
        if (!$setup->tableExists($tableName)) {

            $table = $setup->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'Template ID'
                )
                ->addColumn(
                    'report_id',
                    Table::TYPE_INTEGER,
                    null,
                    ['nullable' => false, 'unsigned' => true],
                    'Report ID'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    [
                        'nullable' => false,
                        'default' => Table::TIMESTAMP_INIT
                    ],
                    'Created At'
                )
                ->addColumn(
                    'clicked_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    [
                        'nullable' => false,
                        'default' => Table::TIMESTAMP_INIT
                    ],
                    'Clicked At'
                )
                ->addColumn(
                    'added_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    [
                        'nullable' => false,
                        'default' => Table::TIMESTAMP_INIT
                    ],
                    'Added To Cart At'
                )
                ->addColumn(
                    'ordered_at',
                    Table::TYPE_TIMESTAMP,
                    null,
                    [
                        'nullable' => false,
                        'default' => Table::TIMESTAMP_INIT
                    ],
                    'Ordered At'
                )->addForeignKey(
                    $setup->getFkName(
                        'magenest_instagramshop_reportlog',
                        'report_id',
                        'magenest_instagramshop_report',
                        'id'),
                    'report_id',
                    $setup->getTable('magenest_instagramshop_report'),
                    'id',
                    Table::ACTION_CASCADE
                )
                ->setComment('Log Datetime For Report Table');
            $setup->getConnection()->createTable($table);
        }
    }

    /**
     * @param SchemaSetupInterface $installer
     */
    private function addUpdateColumn($installer)
    {
        $tableName = $installer->getTable('magenest_instagramshop_report');
        $installer->getConnection()->addColumn(
            $tableName,
            'created_at',
            [
                'type' => Table::TYPE_TIMESTAMP,
                'size' => null,
                'nullable' => false,
                'default' => Table::TIMESTAMP_INIT,
                'comment' => 'Created At'
            ]
        );
        $installer->getConnection()->addColumn(
            $tableName,
            'updated_at',
            [
                'type' => Table::TYPE_TIMESTAMP,
                'size' => null,
                'nullable' => false,
                'default' => Table::TIMESTAMP_INIT_UPDATE,
                'comment' => 'Updated At'
            ]
        );
    }

    /**
     * @param SchemaSetupInterface $installer
     */
    private function addPositionColumn($installer)
    {
        $tableName = $installer->getTable('magenest_instagram_photo');
        $installer->getConnection()->addColumn(
            $tableName,
            'position',
            [
                'type' => Table::TYPE_INTEGER,
                'size' => null,
                'nullable' => true,
                'default' => 0,
                'comment' => 'Position'
            ]
        );
    }

    /**
     * @param SchemaSetupInterface $installer
     */
    private function modifyProductDataColumn($installer)
    {
        $installer->getConnection()->modifyColumn(
            $installer->getTable('magenest_instagramshop_report'),
            'product_data',
            [
                'type' => Table::TYPE_TEXT,
                'size' => null,
                'comment' => 'Product Data JSON'
            ]
        );
    }

    /**
     * @param $setup SchemaSetupInterface
     * @throws \Zend_Db_Exception
     */
    private function createReportTable($setup)
    {
        $tableName = $setup->getTable('magenest_instagramshop_report');
        if (!$setup->tableExists($tableName)) {
            $table_magenest_instagramshop_report = $setup->getConnection()->newTable($tableName);

            $table_magenest_instagramshop_report->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true,],
                'Entity ID'
            )->addColumn(
                'photo_id',
                Table::TYPE_TEXT,
                255,
                ['nullable=>false'],
                'Photo ID'
            )->addColumn(
                'type',
                Table::TYPE_SMALLINT,
                null,
                ['nullable' => false, 'default' => 1],
                'Photo Type'
            )->addColumn(
                'total_click',
                Table::TYPE_BIGINT,
                null,
                ['default' => 0],
                'Total Click'
            )->addColumn(
                'product_data',
                Table::TYPE_TEXT,
                255,
                ['nullable' => true],
                'Product Data JSON'
            )->addIndex(
                $setup->getIdxName($tableName, ['id']),
                ['id']
            )->addIndex(
                $setup->getIdxName($tableName, ['photo_id']),
                ['photo_id']
            )->setComment('Table Magenest Instagram Shop Report');

            $setup->getConnection()->createTable($table_magenest_instagramshop_report);
        }
    }

    /**
     * @param $setup SchemaSetupInterface
     * @throws \Zend_Db_Exception
     */
    private function createHotspotTable($setup)
    {
        $tableName = $setup->getTable('magenest_instagramshop_hotspot');
        if (!$setup->tableExists($tableName)) {
            $table_magenest_instagramshop_hotspot = $setup->getConnection()->newTable($tableName);

            $table_magenest_instagramshop_hotspot->addColumn(
                'hotspot_id',
                Table::TYPE_INTEGER,
                null,
                ['identity' => true, 'nullable' => false, 'primary' => true, 'unsigned' => true,],
                'Entity ID'
            );

            $table_magenest_instagramshop_hotspot->addColumn(
                'photo_id',
                Table::TYPE_TEXT,
                255,
                [],
                'photo_id'
            );

            $table_magenest_instagramshop_hotspot->addColumn(
                'hotspot1_sku',
                Table::TYPE_TEXT,
                null,
                [],
                'hotspot1_sku'
            );

            $table_magenest_instagramshop_hotspot->addColumn(
                'hotspot1_x',
                Table::TYPE_TEXT,
                null,
                [],
                'hotspot1_x'
            );

            $table_magenest_instagramshop_hotspot->addColumn(
                'hotspot1_y',
                Table::TYPE_TEXT,
                null,
                [],
                'hotspot1_y'
            );

            $table_magenest_instagramshop_hotspot->addColumn(
                'hotspot2_sku',
                Table::TYPE_TEXT,
                null,
                [],
                'hotspot2_sku'
            );

            $table_magenest_instagramshop_hotspot->addColumn(
                'hotspot2_x',
                Table::TYPE_TEXT,
                null,
                [],
                'hotspot2_x'
            );

            $table_magenest_instagramshop_hotspot->addColumn(
                'hotspot2_y',
                Table::TYPE_TEXT,
                null,
                [],
                'hotspot2_y'
            );

            $table_magenest_instagramshop_hotspot->addColumn(
                'hotspot3_sku',
                Table::TYPE_TEXT,
                null,
                [],
                'hotspot3_sku'
            );

            $table_magenest_instagramshop_hotspot->addColumn(
                'hotspot3_x',
                Table::TYPE_TEXT,
                null,
                [],
                'hotspot3_x'
            );

            $table_magenest_instagramshop_hotspot->addColumn(
                'hotspot3_y',
                Table::TYPE_TEXT,
                null,
                [],
                'hotspot3_y'
            );

            $table_magenest_instagramshop_hotspot->addColumn(
                'hotspot4_sku',
                Table::TYPE_TEXT,
                null,
                [],
                'hotspot4_sku'
            );

            $table_magenest_instagramshop_hotspot->addColumn(
                'hotspot4_x',
                Table::TYPE_TEXT,
                null,
                [],
                'hotspot4_x'
            );

            $table_magenest_instagramshop_hotspot->addColumn(
                'hotspot4_y',
                Table::TYPE_TEXT,
                null,
                [],
                'hotspot4_y'
            );

            $table_magenest_instagramshop_hotspot->addColumn(
                'hotspot5_sku',
                Table::TYPE_TEXT,
                null,
                [],
                'hotspot5_sku'
            );

            $table_magenest_instagramshop_hotspot->addColumn(
                'hotspot5_x',
                Table::TYPE_TEXT,
                null,
                [],
                'hotspot5_x'
            );

            $table_magenest_instagramshop_hotspot->addColumn(
                'hotspot5_y',
                Table::TYPE_TEXT,
                null,
                [],
                'hotspot5_y'
            );

            $table_magenest_instagramshop_hotspot->addColumn(
                'type',
                Table::TYPE_SMALLINT,
                null,
                [],
                'type'
            );

            $setup->getConnection()->createTable($table_magenest_instagramshop_hotspot);
        }
    }

    /**
     * @param $installer SchemaSetupInterface
     */
    private function createPhotoTable($installer)
    {

        //Install Table magenest_instagram_photo
        $table = $installer->getConnection()->newTable(
            $installer->getTable('magenest_instagram_photo')
        )->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ],
            'Id'
        )->addColumn(
            'photo_id',
            Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'Instagram Photo Id'
        )->addColumn(
            'url',
            Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'Instagram Photo Url'
        )->addColumn(
            'source',
            Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'Instagram Photo Source Link'
        )->addColumn(
            'caption',
            Table::TYPE_TEXT,
            null,
            ['nullable' => true],
            'Instagram Photo Caption'
        )->addColumn(
            'product_id',
            Table::TYPE_TEXT,
            null,
            ['nullable' => true],
            'Created Product Id'
        )->addColumn(
            'likes',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => true],
            'Photo Likes'
        )->addColumn(
            'comments',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => true],
            'Photo Comments'
        )->setComment(
            'Instagram Photo on Store\'s Account'
        );
        $installer->getConnection()->createTable($table);
    }
    /**
     * @param SchemaSetupInterface $installer
     */
    /**
     * @param $installer SchemaSetupInterface
     */
    private function createTaggedTable($installer)
    {
        //Install Table magenest_instagram_taggedphoto
        $table = $installer->getConnection()->newTable(
            $installer->getTable('magenest_instagram_taggedphoto')
        )->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            [
                'identity' => true,
                'unsigned' => true,
                'nullable' => false,
                'primary' => true
            ],
            'Id'
        )->addColumn(
            'photo_id',
            Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'Instagram Photo Id'
        )->addColumn(
            'tag_name',
            Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'Instagram Photo\'s Tag Name'
        )->addColumn(
            'user',
            Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'Instagram Photos User'
        )->addColumn(
            'url',
            Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'Instagram Photo Url'
        )->addColumn(
            'source',
            Table::TYPE_TEXT,
            null,
            ['nullable' => false],
            'Instagram Photo Source Link'
        )->addColumn(
            'caption',
            Table::TYPE_TEXT,
            null,
            ['nullable' => true],
            'Instagram Photo Caption'
        )->addColumn(
            'min_tag_id',
            Table::TYPE_TEXT,
            null,
            ['nullable' => true],
            'Instagram Min Tag Id'
        )->addColumn(
            'likes',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => true],
            'Photo Likes'
        )->addColumn(
            'comments',
            Table::TYPE_INTEGER,
            null,
            ['nullable' => true],
            'Photo Comments'
        )->setComment(
            'Instagram Tagged Photo'
        );
        $installer->getConnection()->createTable($table);
    }

    private function addShowInWidgetColumn($installer)
    {
        $installer->getConnection()->addColumn(
            $installer->getTable('magenest_instagram_photo'),
            'show_in_widget',
            [
                'type' => Table::TYPE_SMALLINT,
                'comment' => 'Show in widget',
                'default' => 1
            ]
        );
    }

    /**
     * @param SchemaSetupInterface $installer
     */
    private function addCreatedAtColumn($installer)
    {
        $installer->getConnection()->addColumn(
            $installer->getTable('magenest_instagram_photo'),
            'created_at',
            [
                'type' => Table::TYPE_DATE,
                'comment' => 'Created At'
            ]
        );
    }
}
