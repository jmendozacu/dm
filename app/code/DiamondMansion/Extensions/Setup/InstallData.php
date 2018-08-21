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

        $attributes = [
            [
                'code' => 'dm_stone_type',
                'label' => 'DM Diamond Type',
                'values' => ['Colorless', 'Yellow', 'Pink', 'Black', 'Setting']
            ],
            [
                'code' => 'dm_stone_shape',
                'label' => 'DM Diamond Shape',
                'values' => array_map('ucfirst', $this->helper->getDesignRingStoneShapes())
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

        foreach ($attributes as $attribute) {
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
    }
}