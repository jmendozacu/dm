<?php
namespace DiamondMansion\Extensions\Block\Adminhtml\Catalog\Product\Edit\Tab;

use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;

class Images extends Options
{
    /**
     * @var string
     */
    public $_imageHelper;

    public function __construct(
        Context $context,
        Registry $registry,
        \DiamondMansion\Extensions\Helper\Image $imageHelper,
        \DiamondMansion\Extensions\Model\OptionsGroup $optionsGroupModel,
        \DiamondMansion\Extensions\Model\ProductOptions $productOptionsModel,
        array $data = []
    )
    {
        parent::__construct($context, $registry, $optionsGroupModel, $productOptionsModel, $data);

        $this->_imageHelper = $imageHelper;

        $this->_template = 'catalog/product/edit/images/' . $this->getProduct()->getTypeId() . '.phtml';
    }
}