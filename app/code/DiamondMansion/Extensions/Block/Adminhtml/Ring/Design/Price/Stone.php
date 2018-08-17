<?php
namespace DiamondMansion\Extensions\Block\Adminhtml\Ring\Design\Price;

use \Magento\Backend\Block\Template;

class Stone extends \Magento\Backend\Block\Template
{
	protected $designRingStonePriceModel;

	public function __construct(
		\Magento\Backend\Block\Template\Context $context,
		\DiamondMansion\Extensions\Model\Ring\Design\Price\Stone $designRingStonePriceModel
	) {
		$this->designRingStonePriceModel = $designRingStonePriceModel;

		parent::__construct($context);
	}

	public function getDefaultShapes()
	{
		return ["asscher", "cushion", "emerald", "heart", "marquise", "oval", "pear", "princess", "radiant", "round", "trilliant"];
	}

	public function getDefaultCarats()
	{
		return ["0.75", "1.00", "1.25", "1.50", "1.75", "2.00", "2.25", "2.50", "2.75", "3.00"];
	}

	public function getDefaultColors()
	{
		return ["d", "e", "f", "g", "h", "i", "j", "fancy light", "fancy yellow", "fancy intense", "fancy black"];
	}

	public function getDefaultClarities() {
		return ["fl", "vvs1", "vvs2", "vs1", "vs2", "si1", "si2", "aaa"];
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