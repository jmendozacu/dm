<?php
namespace DiamondMansion\Extensions\Block\Adminhtml\Ring\Design\Price;

use \Magento\Backend\Block\Template;

class Sidestone extends \Magento\Backend\Block\Template
{
    public $helper;
	protected $designRingSidestonePriceModel;

	public function __construct(
		\Magento\Backend\Block\Template\Context $context,
		\DiamondMansion\Extensions\Helper\Data $helper,
		\DiamondMansion\Extensions\Model\Ring\Design\Price\Sidestone $designRingSidestonePriceModel
	) {
	    $this->helper = $helper;
		$this->designRingSidestonePriceModel = $designRingSidestonePriceModel;

		parent::__construct($context);
	}

    public function getAllPrices()
    {
        $prices = [];

        $collection = $this->designRingSidestonePriceModel->getCollection();

        foreach ($collection as $item) {
            $prices[$item->getColorClarity()][(double)$item->getCarat().""][$item->getShape()] = $item->getPrice();
        }

        return $prices;
    }
}