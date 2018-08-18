<?php
namespace DiamondMansion\Extensions\Block\Adminhtml\Ring\Design\Price;

use \Magento\Backend\Block\Template;

class Stone extends \Magento\Backend\Block\Template
{
    public $helper;
	protected $designRingStonePriceModel;

	public function __construct(
		\Magento\Backend\Block\Template\Context $context,
		\DiamondMansion\Extensions\Helper\Data $helper,
		\DiamondMansion\Extensions\Model\Ring\Design\Price\Stone $designRingStonePriceModel
	) {
	    $this->helper = $helper;
		$this->designRingStonePriceModel = $designRingStonePriceModel;

		parent::__construct($context);
	}

    public function getAllPrices()
    {
        $prices = [];

        $collection = $this->designRingStonePriceModel->getCollection();

        foreach ($collection as $item) {
            $prices[$item->getShape()][$item->getCarat()][$item->getColor()][$item->getClarity()] = $item->getPrice();
        }
        
        return $prices;
    }
}