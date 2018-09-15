<?php
namespace DiamondMansion\Extensions\Ui\Component\DataProvider\Contact\Certificate;

class Listing extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $_helper;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \DiamondMansion\Extensions\Model\ResourceModel\Contact\Certificate\CollectionFactory $contactCertificateCollectionFactory,
        \DiamondMansion\Extensions\Helper\Data $helper,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $contactCertificateCollectionFactory->create();
        $this->_helper = $helper;

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        $contacts = $this->collection->getItems();

        $items = [];
        foreach ($contacts as $contact) {
            $data = $contact->getData();
            if ($data['product_link']) {
                $data['product_link'] = '<a href="' . $data['product_link'] . '" target="_blank">' . $data['product_link'] . '</a>';
            }
            $items[] = $data;
        }

        return [
            'totalRecords' => $this->collection->getSize(),
            'items' => $items,
        ];
    }
}