<?php
namespace DiamondMansion\Extensions\Ui\Component\DataProvider\Customer\Wishlist;

class Listing extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $_helper;
    protected $_customerRepository;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \DiamondMansion\Extensions\Model\ResourceModel\LikeDislike\CollectionFactory $wishlistCollectionFactory,
        \DiamondMansion\Extensions\Helper\Data $helper,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $wishlistCollectionFactory->create();
        $this->_helper = $helper;
        $this->_customerRepository = $customerRepository;

        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        $wishlist = $this->collection
            ->addFieldToFilter('review', 1)
            ->addFieldToFilter('product_options', ['neq' => ''])
            ->getItems();

        $items = [];
        foreach ($wishlist as $item) {
            $data = $item->getData();
            $options = $data['product_options'];

            $email = trim($item->getEmail());
            $html = "";
            $options = json_decode($options, true);

            if (isset($options['image'])) {
                $html = '<div style="display: inline-block; width: 70px; text-align: center; margin: 0 5px; border: 1px solid #e2e2e2;"><a href="' . $options['url'] . '" target="_blank" title="' . $options['name'] . '"><img style="width: 70px; height: 70px;" src="' . $options['image'] . '"/><span style="line-height: 30px;">' . $options['price'] . '</span></a></div>';
            }

            if (!isset($items[$email])) {
                try {
                    $customer = $this->_customerRepository->get($email);
                    $name = $customer->getFirstName() . " " . $customer->getLastName();
                } catch (\Exception $e) {
                    $name = "";
                }

                $items[$email] = [
                    'email' => $email,
                    'name' => $name,
                    'items' => ''
                ];
            }
            $items[$email]['items'] .= $html;
        }

        return [
            'totalRecords' => count($items),
            'items' => array_values($items),
        ];
    }
}