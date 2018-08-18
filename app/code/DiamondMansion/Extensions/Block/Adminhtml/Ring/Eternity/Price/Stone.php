<?php
namespace DiamondMansion\Extensions\Block\Adminhtml\Ring\Eternity\Price;

use \Magento\Backend\Block\Template;

class Stone extends \Magento\Backend\Block\Template
{
    public $helper;
	protected $eternityRingStonePriceModel;

	public function __construct(
		\Magento\Backend\Block\Template\Context $context,
		\DiamondMansion\Extensions\Helper\Data $helper,
		\DiamondMansion\Extensions\Model\Ring\Eternity\Price\Stone $eternityRingStonePriceModel
	) {
	    $this->helper = $helper;
		$this->eternityRingStonePriceModel = $eternityRingStonePriceModel;

		parent::__construct($context);
	}

    public function getAllPrices()
    {
        $prices = [];

        $collection = $this->eternityRingStonePriceModel->getCollection();

        foreach ($collection as $item) {
            $prices[$item->getColorClarity()][(double)$item->getCarat().""][$item->getShape()] = $item->getPrice();
        }

        return $prices;
    }
}