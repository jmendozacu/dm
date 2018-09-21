<?php
/**
 * Mirasvit
 *
 * This source file is subject to the Mirasvit Software License, which is available at https://mirasvit.com/license/.
 * Do not edit or add to this file if you wish to upgrade the to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please refer to http://www.magentocommerce.com for more information.
 *
 * @category  Mirasvit
 * @package   mirasvit/module-seo
 * @version   2.0.85
 * @copyright Copyright (C) 2018 Mirasvit (https://mirasvit.com/)
 */



namespace Mirasvit\Seo\Ui\Template\DataProvider;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Mirasvit\Seo\Model\ResourceModel\Template\CollectionFactory;
use Mirasvit\Seo\Model\Image\ImageFile;
use Mirasvit\Seo\Api\Service\Image\ImageServiceInterface;

class SeoTemplateFormDataProvider extends AbstractDataProvider
{
    public function __construct(
        CollectionFactory $collectionFactory,
        $name,
        $primaryFieldName,
        $requestFieldName,
        ImageFile $imageFile,
        ImageServiceInterface $imageService,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create()->addStoreColumn();
        $this->imageFile = $imageFile;
        $this->imageService = $imageService;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }


    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $storeIds = [];
        if ($data = $this->collection->getData()) { //prepare store_id for multistore
            foreach ($data as $value) {
                $storeIds[$value['template_id']] = $value['store_id'];
            }
        }

        $result = [];
        foreach ($this->collection->getItems() as $item) {
            if (isset($storeIds[$item->getId()])) {  //prepare store_id for multistore
                $item->setData('store_id', $storeIds[$item->getId()]);
            }
            $data = $item->getData();
            $data = $this->prepareImageData($data, 'category_image');
            $result[$item->getId()] = $data;
        }



        return $result;
    }

    /**
     * @param array $data
     * @return array
     */
    private function prepareImageData($data, $imageKey)
    {
        if (isset($data[$imageKey])) {
            $imageName = $data[$imageKey];
            unset($data[$imageKey]);
            if ($this->imageFile->isExist($imageName)) {
                $stat = $this->imageFile ->getStat($imageName);
                $data[$imageKey] = [
                    [
                        'name' => $imageName,
                        'url' => $this->imageService->getCategoryImageUrl($imageName),
                        'size' => isset($stat) ? $stat['size'] : 0,
                        'type' => $this->imageFile->getMimeType($imageName)
                    ]
                ];
            }
        }
        return $data;
    }
}
