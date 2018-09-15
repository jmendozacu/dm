<?php
namespace DiamondMansion\Extensions\Ui\Component\DataProvider\Designer;

class Form extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $helper;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \DiamondMansion\Extensions\Model\ResourceModel\Designer\CollectionFactory $designerCollectionFactory,
        \DiamondMansion\Extensions\Helper\Data $helper,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $designerCollectionFactory->create();
        $this->helper = $helper;

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $designers = $this->collection->getItems();
        $this->loadedData = array();

        foreach ($designers as $designer) {
            $this->loadedData[$designer->getId()]['designer'] = $designer->getData();
            if (!empty($designer->getPhoto())) {
                unset($this->loadedData[$designer->getId()]['designer']['photo']);
                $this->loadedData[$designer->getId()]['designer']['photo'] = [[
                    'name' => $designer->getPhoto(),
                    'url' => $this->helper->getDesignerPhotoUrl($designer->getPhoto()),
                    'size' => filesize($this->helper->getDesignerPhotoDir() . $designer->getPhoto())
                ]];
            }
        }

        return $this->loadedData;
    }
}