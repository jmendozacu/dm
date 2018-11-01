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

        $this->createDesignerTable($setup);

        $this->createOptionsGroupTable($setup);
        $this->createProductOptionsTable($setup);

        $this->createContactRequestsProductTable($setup);
        $this->createContactRequestsCertificateTable($setup);
        $this->createContactRequestsDeliveryTable($setup);
        $this->createContactRequestsOpinionTable($setup);
        $this->createContactRequestsPriceReserveTable($setup);

        $this->createLikeDislikeTable($setup);

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
    private function createDesignerTable($setup) {
        $tableName = $setup->getTable('dm_designer');

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
                    'name',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Designer Name'
                )
                ->addColumn(
                    'description',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Description'
                )
                ->addColumn(
                    'photo',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Photo'
                )
                ->addColumn(
                    'link_facebook',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Facebook Link'
                )
                ->addColumn(
                    'link_twitter',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Twitter Link'
                )
                ->addColumn(
                    'link_google',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Google Link'
                )
                ->addColumn(
                    'link_blog',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Blog Link'
                )
                ->setComment('Designer');
            $setup->getConnection()->createTable($table);
        }
    }

    private function createOptionsGroupTable($setup) {
        $tableName = $setup->getTable('dm_options_group');

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
                    'type',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Product Type'
                )
                ->addColumn(
                    'group',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Options Group Code'
                )
                ->addColumn(
                    'code',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Option Code'
                )
                ->addColumn(
                    'title',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Option Name'
                )
                ->addColumn(
                    'slug',
                    Table::TYPE_TEXT,
                    1,
                    ['nullable' => false],
                    'Option Slug - Must Be 1 lowercase letter (a ~ z)'
                )
                ->addColumn(
                    'is_default',
                    Table::TYPE_INTEGER,
                    1,
                    ['nullable' => false],
                    'Default Flag'
                )
                ->addColumn(
                    'values',
                    Table::TYPE_TEXT,
                    30000,
                    ['nullable' => false],
                    'Special Values'
                )
                ->setComment('DiamondMansion Options Group');
            $setup->getConnection()->createTable($table);

            $optionsGroup = [
                'dm_ring_design' => [
                    'main-stone-type' => [
                        [
                            'natural',
                            'Natural',
                            'c',
                            true,
                            [
                                'children' => [
                                    'main-stone-shape' => ["asscher", "cushion", "emerald", "heart", "marquise", "oval", "pear", "princess", "radiant", "round"],
                                    'main-stone-carat' => ["0.75", "1.00", "1.25", "1.50", "1.75", "2.00", "2.25", "2.50", "2.75", "3.00"],
                                    'main-stone-color' => ["d", "e", "f", "g", "h", "i", "j"],
                                    'main-stone-clarity' => ["fl", "vvs1", "vvs2", "vs1", "vs2", "si1", "si2"],
                                    'main-stone-cert' => ['gia', 'egl-usa', 'both']
                                ]
                            ]
                        ],
                        [
                            'yellow',
                            'Yellow',
                            'c',
                            false,
                            [
                                'children' => [
                                    'main-stone-shape' => ["cushion", "radiant"],
                                    'main-stone-carat' => ["0.75", "1.00", "1.25", "1.50", "1.75", "2.00", "2.25", "2.50", "2.75", "3.00"],
                                    'main-stone-color' => ["fancy-light", "fancy-yellow", "fancy-intense"],
                                    'main-stone-clarity' => ["fl", "vvs1", "vvs2", "vs1", "vs2", "si1", "si2"],
                                    'main-stone-cert' => ['gia', 'egl-usa', 'both']
                                ]
                            ]
                        ],
                        [
                            'pink',
                            'Pink',
                            'p',
                            false,
                            [
                                'children' => [
                                    'main-stone-shape' => ["asscher", "cushion", "emerald", "heart", "marquise", "oval", "pear", "princess", "radiant", "round"],
                                    'main-stone-carat' => ["0.75", "1.00", "1.25", "1.50", "1.75", "2.00", "2.25", "2.50", "2.75", "3.00"],
                                    'main-stone-color' => ["d", "e", "f", "g", "h", "i", "j"],
                                    'main-stone-clarity' => ["fl", "vvs1", "vvs2", "vs1", "vs2", "si1", "si2"],
                                    'main-stone-cert' => ['gia', 'egl-usa', 'both']
                                ]
                            ]
                        ],
                        [
                            'black',
                            'Black',
                            'b',
                            false,
                            [
                                'children' => [
                                    'main-stone-shape' => ["round"],
                                    'main-stone-carat' => ["0.75", "1.00", "1.25", "1.50", "1.75", "2.00", "2.25", "2.50", "2.75", "3.00"],
                                    'main-stone-color' => ["fancy-black"],
                                    'main-stone-clarity' => ["aaa"],
                                    'main-stone-cert' => ['gia', 'egl-usa', 'both']
                                ]
                            ]
                        ],
                        [
                            'setting',
                            'Setting',
                            'n',
                            false,
                            ""
                        ],
                    ],
                    'main-stone-shape' => [
                        ['asscher', 'Asscher', 'a', true, ""],
                        ['cushion', 'Cushion', 'c', false, ""],
                        ['emerald', 'Emerald', 'e', false, ""],
                        ['heart', 'Heart', 'h', false, ""],
                        ['marquise', 'Marquise', 'm', false, ""],
                        ['oval', 'Oval', 'o', false, ""],
                        ['pear', 'Pear', 'p', false, ""],
                        ['princess', 'Princess', 'q', false, ""],
                        ['radiant', 'Radiant', 'r', false, ""],
                        ['round', 'Round', 's', false, ""],
                        ['square-cushion', 'Square Cushion', 't', false, ""],
                        ['long-cushion', 'Long Cushion', 'u', false, ""],
                        ['square-radiant', 'Square Radiant', 'v', false, ""],
                        ['long-radiant', 'Long Radiant', 'w', false, ""],
                    ],
                    'main-stone-carat' => [
                        ['0.75', '0.75', 'a', false, ""],
                        ['1.00', '1.00', 'b', true, ""],
                        ['1.25', '1.25', 'c', false, ""],
                        ['1.50', '1.50', 'd', false, ""],
                        ['1.75', '1.75', 'e', false, ""],
                        ['2.00', '2.00', 'f', false, ""],
                        ['2.25', '2.25', 'g', false, ""],
                        ['2.50', '2.50', 'h', false, ""],
                        ['2.75', '2.75', 'i', false, ""],
                        ['3.00', '3.00', 'j', false, ""],
                        ['3.50', '3.50', 'k', false, ""],
                        ['4.00', '4.00', 'l', false, ""],
                        ['4.50', '4.50', 'm', false, ""],
                        ['5.00', '5.00', 'n', false, ""],
                    ],
                    'main-stone-color' => [
                        ['d', 'D', 'd', false, ""],
                        ['e', 'E', 'e', false, ""],
                        ['f', 'F', 'f', false, ""],
                        ['g', 'G', 'g', true, ""],
                        ['h', 'H', 'h', false, ""],
                        ['i', 'I', 'i', false, ""],
                        ['j', 'J', 'j', false, ""],
                        ['fancy-light', 'Fancy Light', 'k', false, ""],
                        ['fancy-yellow', 'Fancy Yellow', 'l', false, ""],
                        ['fancy-intense', 'Fancy Intense', 'm', false, ""],
                        ['fancy-black', 'Fancy Black', 'n', false, ""],
                        ['d-e', 'D-E', 'o', false, ""],
                        ['e-f', 'E-F', 'p', false, ""],
                        ['f-g', 'F-G', 'q', false, ""],
                        ['g-h', 'G-H', 'r', false, ""],
                        ['i-j', 'I-J', 's', false, ""],
                    ],
                    'main-stone-clarity' => [
                        ['fl', 'FL', 'f', false, ""],
                        ['vvs1', 'VVS1', 's', false, ""],
                        ['vvs2', 'VVS2', 't', false, ""],
                        ['vs1', 'VS1', 'v', false, ""],
                        ['vs2', 'VS2', 'w', false, ""],
                        ['si1', 'SI1', 'l', true, ""],
                        ['si2', 'SI2', 'm', false, ""],
                        ['aaa', 'AAA', 'n', false, ""],
                        ['vvs1-vvs2', 'VVS1-VVS2', 'o', false, ""],
                        ['vs1-vs2', 'VS1-VS2', 'p', false, ""],
                        ['si1-si2', 'SI1-SI2', 'q', false, ""],
                        ['si1+', 'SI1+', 'r', false, ""],
                    ],
                    'main-stone-cert' => [
                        ['gia', 'GIA', 'g', true, ""],
                        ['egl-usa', 'EGL USA', 'e', false, ""],
                        ['both', 'BOTH', 'b', false, ""],
                    ],
                    'setting-options-stone' => [
                        ['white-diamond', 'White Diamond', 'w', true, ""],
                        ['yellow-diamond', 'Yellow Diamond', 'y', false, ""],
                        ['other', 'Other', 'o', false, ""],
                        ['gemstone', 'Gemstone', 'gw', false, ""],
                        ['moissanite', 'Moissanite', 'm', false, ""],
                        ['cubic-zirconium', 'Cubic Zirconium', 'c', false, ""],
                        ['pearl', 'Pearl', 'p', false, ""],
                    ],
                    'setting-options-size' => [
                        ['size', 'Size', '', true, ""]
                    ],
                    'metal' => [
                        ['14k-white-gold', '14K White Gold', 'c', true, ""],
                        ['18k-white-gold', '18K White Gold', 'i', false, ""],
                        ['14k-yellow-gold', '14K Yellow Gold', 'd', false, ""],
                        ['18k-yellow-gold', '18K Yellow Gold', 'j', false, ""],
                        ['14k-rose-gold', '14K Rose Gold', 'n', false, ""],
                        ['18k-rose-gold', '18K Rose Gold', 'e', false, ""],
                        ['14k-tri-color-gold', '14K Tri-Color Gold', 'o', false, ""],
                        ['18k-tri-color-gold', '18K Tri-Color Gold', 'f', false, ""],
                        ['14k-two-tone-yellow-gold', '14K Two Tone Yellow Gold', 'b', false, ""],
                        ['18k-two-tone-yellow-gold', '18K Two Tone Yellow Gold', 'h', false, ""],
                        ['14k-two-tone-rose-gold', '14K Two Tone Rose Gold', 'a', false, ""],
                        ['18k-two-tone-rose-gold', '18K Two Tone Rose Gold', 'g', false, ""],
                        ['platinum', 'Platinum', 'k', false, ""],
                        ['platinum-two-tone-rose', 'Platinum Two Tone Rose', 'l', false, ""],
                        ['platinum-two-tone-yellow', 'Platinum Two Tone Yellow', 'm', false, ""],
                    ],
                    'band' => [
                        ['bridal-set', 'Bridal Set', 'i', false, ""],
                        ['no-band', 'No Band', 'n', true, ""],
                    ],
                    'ring-size' => [
                        ['3', '3', 'a', false, ""],
                        ['3.25', '3.25', 'b', false, ""],
                        ['3.5', '3.5', 'c', false, ""],
                        ['3.75', '3.75', 'd', false, ""],
                        ['4', '4', 'e', false, ""],
                        ['4.25', '4.25', 'f', false, ""],
                        ['4.5', '4.5', 'g', false, ""],
                        ['4.75', '4.75', 'h', false, ""],
                        ['5', '5', 'i', true, ""],
                        ['5.25', '5.25', 'j', false, ""],
                        ['5.5', '5.5', 'k', false, ""],
                        ['5.75', '5.75', 'l', false, ""],
                        ['6', '6', 'm', false, ""],
                        ['6.25', '6.25', 'n', false, ""],
                        ['6.5', '6.5', 'o', false, ""],
                        ['6.75', '6.75', 'p', false, ""],
                        ['7', '7', 'q', false, ""],
                        ['7.25', '7.25', 'r', false, ""],
                        ['7.5', '7.5', 's', false, ""],
                        ['7.75', '7.75', 't', false, ""],
                        ['8', '8', 'u', false, ""],
                        ['8.25', '8.25', 'v', false, ""],
                        ['8.5', '8.5', 'w', false, ""],
                        ['8.75', '8.75', 'x', false, ""],
                        ['9', '9', 'y', false, ""],
                        ['9.25', '9.25', 'z', false, ""],
                        ['9.5', '9.5', 'A', false, ""],
                        ['9.75', '9.75', 'B', false, ""],
                        ['10', '10', 'C', false, ""],
                    ],
                    'side-stone-shape' => [
                        ['round', 'Round', 'r', true, ""],
                        ['princess', 'Princess', 'q', false, ""],
                        ['asscher', 'Asscher', 'a', false, ""],
                        ['emerald', 'Emerald', 'e', false, ""],
                        ['cushion', 'Cushion', 'c', false, ""],
                        ['radiant', 'Radiant', 'i', false, ""],
                        ['oval', 'Oval', 'o', false, ""],
                        ['trillion', 'Trillion', 't', false, ""],
                        ['pear', 'Pear', 'p', false, ""],
                        ['marquise', 'Marquise', 'm', false, ""],
                        ['heart', 'Heart', 'h', false, ""],
                        ['baguette', 'Baguette', 'b', false, ""],
                        ['trapezoid', 'Trapezoid', 'g', false, ""],
                        ['halfmoon', 'Halfmoon', 'f', false, ""],
                        ['bullet', 'Bullet', 'd', false, ""],
                    ],
                    'side-stone-carat' => [
                        ['0.005', '0.005', 'a', false, ""],
                        ['0.01', '0.01', 'b', false, ""],
                        ['0.015', '0.015', 'c', false, ""],
                        ['0.02', '0.02', 'd', false, ""],
                        ['0.025', '0.025', 'e', false, ""],
                        ['0.03', '0.03', 'f', false, ""],
                        ['0.035', '0.035', 'g', false, ""],
                        ['0.04', '0.04', 'h', false, ""],
                        ['0.045', '0.045', 'i', false, ""],
                        ['0.05', '0.05', 'j', false, ""],
                        ['0.1', '0.1', 'k', false, ""],
                        ['0.15', '0.15', 'l', false, ""],
                        ['0.2', '0.2', 'm', false, ""],
                        ['0.25', '0.25', 'n', false, ""],
                        ['0.3', '0.3', 'o', false, ""],
                        ['0.35', '0.35', 'p', false, ""],
                        ['0.4', '0.4', 'q', false, ""],
                        ['0.45', '0.45', 'r', false, ""],
                        ['0.5', '0.5', 's', true, ""],
                        ['0.55', '0.55', 't', false, ""],
                        ['0.6', '0.6', 'u', false, ""],
                        ['0.65', '0.65', 'v', false, ""],
                        ['0.7', '0.7', 'w', false, ""],
                        ['0.75', '0.75', 'x', false, ""],
                        ['0.8', '0.8', 'y', false, ""],
                        ['0.85', '0.85', 'z', false, ""],
                        ['0.9', '0.9', 'A', false, ""],
                        ['0.95', '0.95', 'B', false, ""],
                        ['1', '1', 'C', false, ""],
                    ],
                    'side-stone-color-clarity' => [
                        ['f-g/vs', 'F-G/VS', 'f', true, ""],
                        ['g-h/si', 'G-H/SI', 'g', false, ""],
                    ],
                    'others' => [
                        ['fixed-main-stone-price', 'Fixed Main Stone Price', '', false, ""],
                        ['exclude-side-stone-price', 'Exclude Side Stone Price', '', false, ""],
                    ]
                ],
                'dm_ring_eternity' => [
                    'stone-type' => [
                        [
                            'natural',
                            'Natural',
                            'c',
                            true,
                            [
                                'children' => [
                                    'stone-shape' => ["asscher", "cushion", "emerald", "heart", "marquise", "oval", "pear", "princess", "radiant", "round", "trillion", "baguette", "Trapezoid", "halfmoon", "bullet"],
                                    'stone-carat' => ["0.05", "0.1", "0.15", "0.2", "0.25", "0.33", "0.4", "0.5"],
                                    'stone-color-clarity' => ["f-g/vs", "g-h/si"],
                                    'metal' => [
                                        "14k-white-gold", "18k-white-gold",
                                        "14k-yellow-gold", "18k-yellow-gold",
                                        "14k-rose-gold", "18k-rose-gold",
                                        "14k-tri-color-gold", "18k-tri-color-gold",
                                        "14k-two-tone-yellow-gold", "18k-two-tone-yellow-gold",
                                        "14k-two-tone-rose-gold", "18k-two-tone-rose-gold",
                                        "platinum", "platinum-two-tone-rose", "platinum-two-tone-yellow",
                                    ]
                                ]
                            ]
                        ],
                        [
                            'yellow',
                            'Yellow',
                            'c',
                            false,
                            [
                                'children' => [
                                    'stone-shape' => ["cushion", "radiant"],
                                    'stone-carat' => ["0.05", "0.1", "0.15", "0.2", "0.25", "0.33", "0.4", "0.5"],
                                    'stone-color-clarity' => ["f-g/vs", "g-h/si"],
                                    'metal' => [
                                        "14k-white-gold", "18k-white-gold",
                                        "14k-yellow-gold", "18k-yellow-gold",
                                        "14k-rose-gold", "18k-rose-gold",
                                        "14k-tri-color-gold", "18k-tri-color-gold",
                                        "14k-two-tone-yellow-gold", "18k-two-tone-yellow-gold",
                                        "14k-two-tone-rose-gold", "18k-two-tone-rose-gold",
                                        "platinum", "platinum-two-tone-rose", "platinum-two-tone-yellow",
                                    ]
                                ]
                            ]
                        ],
                        [
                            'black',
                            'Black',
                            'b',
                            false,
                            [
                                'children' => [
                                    'stone-shape' => ["round"],
                                    'stone-carat' => ["0.05", "0.1", "0.15", "0.2", "0.25", "0.33", "0.4", "0.5"],
                                    'stone-color-clarity' => ["f-g/vs", "g-h/si"],
                                    'metal' => [
                                        "14k-white-gold", "18k-white-gold",
                                        "14k-yellow-gold", "18k-yellow-gold",
                                        "14k-rose-gold", "18k-rose-gold",
                                        "14k-tri-color-gold", "18k-tri-color-gold",
                                        "14k-two-tone-yellow-gold", "18k-two-tone-yellow-gold",
                                        "14k-two-tone-rose-gold", "18k-two-tone-rose-gold",
                                        "platinum", "platinum-two-tone-rose", "platinum-two-tone-yellow",
                                    ]
                                ]
                            ]
                        ],
                    ],
                    'stone-shape' => [
                        ['round', 'Round', 'r', true, ""],
                        ['princess', 'Princess', 'q', false, ""],
                        ['asscher', 'Asscher', 'a', false, ""],
                        ['emerald', 'Emerald', 'e', false, ""],
                        ['cushion', 'Cushion', 'c', false, ""],
                        ['radiant', 'Radiant', 'i', false, ""],
                        ['oval', 'Oval', 'o', false, ""],
                        ['trillion', 'Trillion', 't', false, ""],
                        ['pear', 'Pear', 'p', false, ""],
                        ['marquise', 'Marquise', 'm', false, ""],
                        ['heart', 'Heart', 'h', false, ""],
                        ['baguette', 'Baguette', 'b', false, ""],
                        ['trapezoid', 'Trapezoid', 'g', false, ""],
                        ['halfmoon', 'Halfmoon', 'f', false, ""],
                        ['bullet', 'Bullet', 'd', false, ""],
                    ],
                    'stone-carat' => [
                        ['0.05', '0.05', 'j', false, ""],
                        ['0.1', '0.1', 'k', false, ""],
                        ['0.15', '0.15', 'l', false, ""],
                        ['0.2', '0.2', 'm', false, ""],
                        ['0.25', '0.25', 'n', false, ""],
                        ['0.33', '0.33', 'o', false, ""],
                        ['0.4', '0.4', 'q', false, ""],
                        ['0.5', '0.5', 's', true, ""],
                    ],
                    'stone-color-clarity' => [
                        ['f-g/vs', 'F-G/VS', 'f', true, ""],
                        ['g-h/si', 'G-H/SI', 'g', false, ""],
                    ],
                    'metal' => [
                        ['14k-white-gold', '14K White Gold', 'c', true, ""],
                        ['18k-white-gold', '18K White Gold', 'i', false, ""],
                        ['14k-yellow-gold', '14K Yellow Gold', 'd', false, ""],
                        ['18k-yellow-gold', '18K Yellow Gold', 'j', false, ""],
                        ['14k-rose-gold', '14K Rose Gold', 'n', false, ""],
                        ['18k-rose-gold', '18K Rose Gold', 'e', false, ""],
                        ['14k-tri-color-gold', '14K Tri-Color Gold', 'o', false, ""],
                        ['18k-tri-color-gold', '18K Tri-Color Gold', 'f', false, ""],
                        ['14k-two-tone-yellow-gold', '14K Two Tone Yellow Gold', 'b', false, ""],
                        ['18k-two-tone-yellow-gold', '18K Two Tone Yellow Gold', 'h', false, ""],
                        ['14k-two-tone-rose-gold', '14K Two Tone Rose Gold', 'a', false, ""],
                        ['18k-two-tone-rose-gold', '18K Two Tone Rose Gold', 'g', false, ""],
                        ['platinum', 'Platinum', 'k', false, ""],
                        ['platinum-two-tone-rose', 'Platinum Two Tone Rose', 'l', false, ""],
                        ['platinum-two-tone-yellow', 'Platinum Two Tone Yellow', 'm', false, ""],
                    ],
                    'ring-size' => [
                        ['3', '3', 'a', false, ""],
                        ['3.25', '3.25', 'b', false, ""],
                        ['3.5', '3.5', 'c', false, ""],
                        ['3.75', '3.75', 'd', false, ""],
                        ['4', '4', 'e', false, ""],
                        ['4.25', '4.25', 'f', false, ""],
                        ['4.5', '4.5', 'g', false, ""],
                        ['4.75', '4.75', 'h', false, ""],
                        ['5', '5', 'i', true, ""],
                        ['5.25', '5.25', 'j', false, ""],
                        ['5.5', '5.5', 'k', false, ""],
                        ['5.75', '5.75', 'l', false, ""],
                        ['6', '6', 'm', false, ""],
                        ['6.25', '6.25', 'n', false, ""],
                        ['6.5', '6.5', 'o', false, ""],
                        ['6.75', '6.75', 'p', false, ""],
                        ['7', '7', 'q', false, ""],
                        ['7.25', '7.25', 'r', false, ""],
                        ['7.5', '7.5', 's', false, ""],
                        ['7.75', '7.75', 't', false, ""],
                        ['8', '8', 'u', false, ""],
                        ['8.25', '8.25', 'v', false, ""],
                        ['8.5', '8.5', 'w', false, ""],
                        ['8.75', '8.75', 'x', false, ""],
                        ['9', '9', 'y', false, ""],
                        ['9.25', '9.25', 'z', false, ""],
                        ['9.5', '9.5', 'A', false, ""],
                        ['9.75', '9.75', 'B', false, ""],
                        ['10', '10', 'C', false, ""],
                    ],
                    'order-type' => [
                        ['default', "Default", "a", true, ""],
                        ['10%-deposit', "10% Deposit", "b", false, ""],
                        ['home-try-on', "Home Try On", "c", false, ""],
                    ],
                ]
            ];

            foreach ($optionsGroup as $type => $groups) {
                foreach ($groups as $group => $options) {
                    foreach ($options as $option) {
                        $data = [
                            'type' => $type,
                            'group' => $group,
                            'code' => $option[0],
                            'title' => $option[1],
                            'slug' => $option[2],
                            'is_default' => $option[3],
                            'values' => ($option[4] == "")?:json_encode($option[4])
                        ];
                        $setup->getConnection()->insert($tableName, $data);
                    }
                }
            }
        }
    }
    private function createProductOptionsTable($setup) {
        $tableName = $setup->getTable('dm_product_options');

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
                    'product_id',
                    Table::TYPE_INTEGER,
                    11,
                    ['nullable' => false],
                    'Product ID'
                )
                ->addColumn(
                    'group',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Options Group Code'
                )
                ->addColumn(
                    'code',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Option Code'
                )
                ->addColumn(
                    'is_default',
                    Table::TYPE_INTEGER,
                    1,
                    ['nullable' => false],
                    'Default Flag'
                )
                ->addColumn(
                    'values',
                    Table::TYPE_TEXT,
                    30000,
                    ['nullable' => false],
                    'Special Values'
                )
                ->setComment('DiamondMansion Product Options');
            $setup->getConnection()->createTable($table);
        }
    }

    private function createContactRequestsProductTable($setup) {
        $tableName = $setup->getTable('dm_contact_product');

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
                    'name',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Customer Name'
                )
                ->addColumn(
                    'phone',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Customer Phone'
                )
                ->addColumn(
                    'email',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Customer Email'
                )
                ->addColumn(
                    'product_type',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Product Type'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_DATETIME,
                    255,
                    ['nullable' => false],
                    'Request Date'
                )
                ->addColumn(
                    'preferred_metal',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Preferred Metal'
                )
                ->addColumn(
                    'price_range',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Price Range'
                )
                ->addColumn(
                    'message',
                    Table::TYPE_TEXT,
                    32767,
                    ['nullable' => false],
                    'Message'
                )
                ->addColumn(
                    'images',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Images'
                )
                ->setComment('Contact Requests - Product');
            $setup->getConnection()->createTable($table);
        }
    }
    private function createContactRequestsCertificateTable($setup) {
        $tableName = $setup->getTable('dm_contact_certificate');

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
                    'name',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Customer Name'
                )
                ->addColumn(
                    'phone',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Customer Phone'
                )
                ->addColumn(
                    'email',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Customer Email'
                )
                ->addColumn(
                    'product_name',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Product Name'
                )
                ->addColumn(
                    'product_link',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Product Link'
                )
                ->addColumn(
                    'product_price',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Product Price'
                )
                ->addColumn(
                    'message',
                    Table::TYPE_TEXT,
                    32767,
                    ['nullable' => false],
                    'Message'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    255,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                    'Create date'
                )
                ->setComment('Contact Requests - Certificate');
            $setup->getConnection()->createTable($table);
        }
    }
    private function createContactRequestsDeliveryTable($setup) {
        $tableName = $setup->getTable('dm_contact_delivery');

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
                    'name',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Customer Name'
                )
                ->addColumn(
                    'phone',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Customer Phone'
                )
                ->addColumn(
                    'email',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Customer Email'
                )
                ->addColumn(
                    'product_name',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Product Name'
                )
                ->addColumn(
                    'product_link',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Product Link'
                )
                ->addColumn(
                    'product_price',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Product Price'
                )
                ->addColumn(
                    'message',
                    Table::TYPE_TEXT,
                    32767,
                    ['nullable' => false],
                    'Message'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    255,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                    'Create date'
                )
                ->setComment('Contact Requests - Delivery');
            $setup->getConnection()->createTable($table);
        }
    }
    private function createContactRequestsOpinionTable($setup) {
        $tableName = $setup->getTable('dm_contact_opinion');

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
                    'name',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Customer Name'
                )
                ->addColumn(
                    'email',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Customer Email'
                )
                ->addColumn(
                    'product_name',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Product Name'
                )
                ->addColumn(
                    'product_link',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Product Link'
                )
                ->addColumn(
                    'message',
                    Table::TYPE_TEXT,
                    32767,
                    ['nullable' => false],
                    'Message'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    255,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                    'Create date'
                )
                ->setComment('Contact Requests - Expert Opinion');
            $setup->getConnection()->createTable($table);
        }
    }
    private function createContactRequestsPriceReserveTable($setup) {
        $tableName = $setup->getTable('dm_contact_pricereserve');

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
                    'name',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Customer Name'
                )
                ->addColumn(
                    'phone',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Customer Phone'
                )
                ->addColumn(
                    'email',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Customer Email'
                )
                ->addColumn(
                    'product_name',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Product Name'
                )
                ->addColumn(
                    'product_link',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Product Link'
                )
                ->addColumn(
                    'product_price',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Product Price'
                )
                ->addColumn(
                    'message',
                    Table::TYPE_TEXT,
                    32767,
                    ['nullable' => false],
                    'Message'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_TIMESTAMP,
                    255,
                    ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
                    'Create date'
                )
                ->setComment('Contact Requests - Price Reserve');
            $setup->getConnection()->createTable($table);
        }
    }

    private function createLikeDislikeTable($setup) {
        $tableName = $setup->getTable('dm_product_like_dislike');

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
                    'product_id',
                    Table::TYPE_INTEGER,
                    11,
                    ['nullable' => false],
                    'Customer Name'
                )
                ->addColumn(
                    'customer_ip',
                    Table::TYPE_TEXT,
                    255,
                    ['nullable' => false],
                    'Customer Phone'
                )
                ->addColumn(
                    'review',
                    Table::TYPE_INTEGER,
                    11,
                    ['nullable' => false],
                    'Customer Email'
                )
                ->setComment('Product Likes and Dislikes');
            $setup->getConnection()->createTable($table);
        }
    }    
}