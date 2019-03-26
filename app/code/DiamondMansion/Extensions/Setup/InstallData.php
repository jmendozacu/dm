<?php
namespace DiamondMansion\Extensions\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;
    private $helper;

    public function __construct(
        EavSetupFactory $eavSetupFactory,
        \DiamondMansion\Extensions\Helper\Data $helper
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->helper = $helper;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $multiSelectAttributes = [
            [
                'code' => 'dm_stone_type',
                'label' => 'DM Diamond Type',
                'values' => ['Natural', 'Yellow', 'Pink', 'Black', 'Setting']
            ],
            [
                'code' => 'dm_stone_shape',
                'label' => 'DM Diamond Shape',
                'values' => array_map('ucwords', $this->helper->getDesignRingStoneShapes())
            ],
            [
                'code' => 'dm_band',
                'label' => 'DM Band',
                'values' => ['Bridal Set', 'No Band']
            ],
            [
                'code' => 'dm_metal',
                'label' => 'DM Metal',
                'values' => ['White Gold', 'Yellow Gold', 'Rose Gold', 'Tri-Color Gold', 'Platinum']
            ],
            [
                'code' => 'dm_design_collection',
                'label' => 'DM Design Collection',
                'values' => [
                    'Top25', 'Vintage', 'Antique', 'Art Deco', 'Modern',
                    'Stylish', 'Celtic', 'Hearts', 'Micro Pave', 'Classic',
                    'Celebrity', 'European', 'Euro Shank', 'Two Tone', 'Knots',
                    'Pattern', 'Cluster', 'Claddagh', 'Trendy', 'Filigree',
                    'Infinity', 'Edwardian', 'Trellis', 'Trapezoid Accents', 'Baguette Accents',
                    'Trillion Accents', 'Sapphire Accents', 'Ruby Accents', 'Canary Accents', 'Milgrains',
                    'Double Halo', 'Eternity', 'Tension', 'Bar Set', 'Simple',
                    'Cathedral', 'Black Accents', 'Split Shank', 'Hand Engraved', 'Art Nouveau',
                    'Classic', '5 Stone', 'Unusual', 'Contemporary'
                ]
            ],
            [
                'code' => 'dm_setting_style',
                'label' => 'DM Setting Style',
                'values' => [
                    'Solitaire', 'Three Stone', 'Pave', 'Halo',
                    'Channel Set', 'Gemstone', 'Bezel Set', 'Split Shank'
                ]
            ],
            [
                'code' => 'dm_order_type',
                'label' => 'DM Order Type',
                'values' => ['10% Deposit', 'Home Try On']
            ],
            [
                'code' => 'dm_designer',
                'label' => 'DM Designer',
                'values' => ['Verragio', 'Parade', 'All Designer']
            ],
        ];

        foreach ($multiSelectAttributes as $attribute) {
            if ($eavSetup->getAttributeId('catalog_product', $attribute['code'])) {
                continue;
            }

            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                $attribute['code'],
                [
                    'type' => 'varchar',
                    'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                    'frontend' => '',
                    'label' => $attribute['label'],
                    'input' => 'multiselect',
                    'class' => '',
                    'source' => '',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => true,
                    'default' => '',
                    'searchable' => false,
                    'filterable' => 2,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => true,
                    'unique' => false,
                    'apply_to' => '',
                    'option' => [
                        'values' => $attribute['values']
                    ]
                ]
            );
        }

        $textAttributes = [
            [
                'code' => 'dm_likes',
                'label' => 'Likes',
            ],
            [
                'code' => 'dm_dislikes',
                'label' => 'Dislikes',
            ],
            [
                'code' => "dmz_design_no_stones",
                'label' => 'Design Number of Stones',
            ],
            [
                'code' => "dmz_design_dimensions",
                'label' => "Dimensions",
            ],
            [
                'code' => "dmz_design_max_width",
                'label' => 'Maximum Width'
            ],
            [
                'code' => "dmz_design_min_width",
                'label' => 'Minimum Width'
            ],
            [
                'code' => "dmz_design_shank",
                'label' => 'Shank'
            ],
            [
                'code' => "dmz_design_style_setting",
                'label' => 'Style & Setting'
            ],
            [
                'code' => "dmz_design_total_carat_weight",
                'label' => 'Total Carat Weight',
            ],
            [
                'code' => "dmz_side_type_shape",
                'label' => 'Side Stone Type & Shape',
            ],
            [
                'code' => "dmz_center_type_shape",
                'label' => 'Center Stone Type & Shape',
            ],
            [
                'code' => "dmz_center_weight",
                'label' => 'Center Stone Weight'
            ],
            [
                'code' => "dmz_center_color",
                'label' => 'Center Stone Color'
            ],
            [
                'code' => "dmz_center_clarity",
                'label' => 'Center Stone Clarity',
            ],
            [
                'code' => "dmz_center_quality",
                'label' => 'Center Stone Quality',
            ],
            [
                'code' => "dmz_center_dimensions",
                'label' => 'Measurements'
            ],
            [
                'code' => "dmz_center_cert_link",
                'label' => 'Certification Link'
            ],
            [
                'code' => "dmz_center_min_size",
                'label' => 'Min Size',
            ],
            [
                'code' => "dmz_center_max_size",
                'label' => 'Max Size'
            ],
            [
                'code' => "dmz_center_polish",
                'label' => "Polish",
            ],
            [
                'code' => "dmz_center_symmetry",
                'label' => 'Symmetry'
            ],
            [
                'code' => "dmz_center_depth",
                'label' => 'Depth(%)'
            ],
            [
                'code' => "dmz_center_ratio",
                'label' => 'Ratio'
            ],
            [
                'code' => "dmz_center_girdle",
                'label' => 'Girdle'
            ],
            [
                'code' => "dmz_center_crown_height",
                'label' => 'Crown Height'
            ],
            [
                'code' => "dmz_center_crown_angle",
                'label' => 'Crown Angle'
            ],
            [
                'code' => "dmz_center_pavilion_depth",
                'label' => 'Pavilion Depth'
            ],
            [
                'code' => "dmz_center_pavilion_angle",
                'label' => 'Pavilion Angle'
            ],
            [
                'code' => "dmz_center_star_length",
                'label' => 'Star Length',
            ],
            [
                'code' => "dmz_center_lower_half",
                'label' => 'Lower Half'
            ],
            [
                'code' => "dmz_center_fluorescence",
                'label' => "Fluorescence"
            ],
            [
                'code' => "dmz_center_treatment",
                'label' => 'Treatment'
            ],
            [
                'code' => "dmz_center_culet",
                'label' => 'Culet'
            ],
            [
                'code' => "dmz_center_table",
                'label' => 'Table',
            ],
            [
                'code' => "dmz_side_carat",
                'label' => 'Side Stone Carat'
            ],
            [
                'code' => "dmz_side_color",
                'label' => 'Side Stone Color'
            ],
            [
                'code' => "dmz_side_clarity",
                'label' => 'Side Stone Clarity'
            ],
            [
                'code' => "dmz_side_quality",
                'label' => 'Side Stone Quality'
            ],
            [
                'code' => "dmz_side_count",
                'label' => 'Side Stone Number'
            ],
            [
                'code' => "dmz_center_cert_number",
                'label' => 'Certification Number'
            ],
            [
                'code' => "dmz_design_support_shape",
                'label' => 'Can be set with'
            ],
            [
                'code' => "dm_delivery_dates",
                'label' => 'Delivery Dates'
            ],
            [
                'code' => "dmz_design_elements",
                'label' => 'Design Elements'
            ],
            [
                'code' => "dmz_design_style",
                'label' => 'Design Style'
            ],
            [
                'code' => "dmz_center_cert",
                'label' => 'Certification'
            ],
            [
                'code' => "dmc_subname",
                'label' => 'Subname'
            ]
        ];

        foreach ($textAttributes as $attribute) {
            if ($eavSetup->getAttributeId('catalog_product', $attribute['code'])) {
                continue;
            }

            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                $attribute['code'],
                [
                    'type' => 'varchar',
                    'backend' => '',
                    'frontend' => '',
                    'label' => $attribute['label'],
                    'input' => 'text',
                    'class' => '',
                    'source' => '',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => true,
                    'default' => '',
                    'searchable' => false,
                    'filterable' => 0,
                    'comparable' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => true,
                    'unique' => false,
                    'apply_to' => ''
                ]
            );
        }

        $configurableAttributes = [
            [
                'code' => 'dmc_metal',
                'label' => 'Metal',
                'values' => [
                    '14k-white-gold' => '14k White Gold',
                    '18k-white-gold' => '18k White Gold',
                    '14k-yellow-gold' => '14k Yellow Gold',
                    '18k-yellow-gold' => '18k Yellow Gold',
                    '14k-rose-gold' => '14k Rose Gold',
                    '18k-rose-gold' => '18k Rose Gold',
                    '14k-tri-color-gold' => '14k Tri-Color Gold',
                    '18k-tri-color-gold' => '18k Tri-Color Gold',
                    '14k-two-tone-yellow-gold' => '14k Two-Tone Yellow Gold',
                    '18k-two-tone-yellow-gold' => '18k Two-Tone Yellow Gold',
                    '14k-two-tone-rose-gold' => '14k Two-Tone Rose Gold',
                    '18k-two-tone-rose-gold' => '18k Two-Tone Rose Gold',
                    'platinum' => 'Platinum',
                    'platinum-two-tone-yellow-gold' => 'Platinum Two-Tone Yellow Gold',
                    'platinum-two-tone-rose-gold' => 'Platinum Two-Tone Rose Gold',
                ]
            ],
        ];

        foreach ($configurableAttributes as $attribute) {
            if ($eavSetup->getAttributeId('catalog_product', $attribute['code'])) {
                continue;
            }

            $eavSetup->addAttribute(
                \Magento\Catalog\Model\Product::ENTITY,
                $attribute['code'],
                [
                    'type' => 'int',
                    'backend' => '',
                    'frontend' => '',
                    'label' => $attribute['label'],
                    'input' => 'select',
                    'class' => '',
                    'source' => '',
                    'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible' => true,
                    'required' => false,
                    'user_defined' => true,
                    'default' => '',
                    'searchable' => false,
                    'filterable' => 2,
                    'comparable' => false,
                    'visible_on_front' => true,
                    'used_in_product_listing' => true,
                    'unique' => false,
                    'apply_to' => '',
                    'option' => [
                        'values' => array_values($attribute['values'])
                    ]
                ]
            );
        }
    }
}