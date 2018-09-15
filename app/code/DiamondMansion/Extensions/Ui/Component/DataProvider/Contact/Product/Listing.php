<?php
namespace DiamondMansion\Extensions\Ui\Component\DataProvider\Contact\Product;

class Listing extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $_helper;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \DiamondMansion\Extensions\Model\ResourceModel\Contact\Product\CollectionFactory $contactProductCollectionFactory,
        \DiamondMansion\Extensions\Helper\Data $helper,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $contactProductCollectionFactory->create();
        $this->_helper = $helper;

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        $contacts = $this->collection->getItems();

        $items = [];
        foreach ($contacts as $contact) {
            $data = $contact->getData();
            if ($data['images']) {
                $images = explode(',', $data['images']);
                $imageElms = [];
                foreach ($images as $image) {
                    $imageElms[] = '<a href="' . $this->_helper->getMediaUrl() . 'contact/product/' . $image . '" target="_blank"><img style="width: 70px; height: 70px;" src="' . $this->_helper->getMediaUrl() . 'contact/product/' . $image . '"/></a>';
                }
                $data['images'] = implode('', $imageElms);
            }
            $items[] = $data;
        }

        return [
            'totalRecords' => $this->collection->getSize(),
            'items' => $items,
        ];
    }
}