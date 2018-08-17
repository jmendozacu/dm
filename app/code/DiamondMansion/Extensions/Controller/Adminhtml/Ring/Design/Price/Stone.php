<?php
namespace DiamondMansion\Extensions\Controller\Adminhtml\Ring\Design\Price;
 
class Stone extends \DiamondMansion\Extensions\Controller\Adminhtml\Base
{

    protected $designRingStonePriceFactory;

    /**
     * Constructor
     *
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \DiamondMansion\Extensions\Model\Ring\Design\Price\StoneFactory $designRingStonePrice
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \DiamondMansion\Extensions\Model\Ring\Design\Price\StoneFactory $designRingStonePriceFactory
    ) {
        parent::__construct($context, $resultPageFactory);

        $this->designRingStonePriceFactory = $designRingStonePriceFactory;
    }

    public function execute()
    {
        //$designRingStonePriceModel = $this->designRingStonePriceFactory->create();
        //$collection = $designRingStonePriceModel->getCollection();

        return parent::execute();
    }
}