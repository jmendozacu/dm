<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PageSpeedOptimizer
 */


namespace Amasty\PageSpeedOptimizer\Model\Image\DataProvider;

use Amasty\PageSpeedOptimizer\Api\Data\ImageSettingInterface;
use Amasty\PageSpeedOptimizer\Api\ImageSettingRepositoryInterface;
use Amasty\PageSpeedOptimizer\Controller\Adminhtml\RegistryConstants;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Filesystem;
use Magento\Framework\UrlInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Class Form
 */
class Form extends AbstractDataProvider
{
    /**
     * @var ImageSettingRepositoryInterface
     */
    private $repository;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @var array
     */
    private $loadedData;

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var Filesystem\Directory\ReadInterface
     */
    private $mediaDirectory;

    /**
     * @var array
     */
    private $excludeFolders;

    /**
     * @var UrlInterface
     */
    private $url;

    public function __construct(
        ImageSettingRepositoryInterface $repository,
        DataPersistorInterface $dataPersistor,
        RequestInterface $request,
        UrlInterface $url,
        Filesystem $filesystem,
        $name,
        $primaryFieldName,
        $requestFieldName,
        array $meta = [],
        array $data = []
    ) {
        $this->repository = $repository;
        $this->dataPersistor = $dataPersistor;
        $this->collection = $this->repository->getImageSettingCollection();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->request = $request;
        $this->mediaDirectory = $filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $this->url = $url;
    }

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $this->getCollection()->addFieldToSelect(ImageSettingInterface::IMAGE_SETTING_ID);
        $data = parent::getData();
        if (isset($data['items'][0])) {
            $imageSettingId = $data['items'][0][ImageSettingInterface::IMAGE_SETTING_ID];
            $imageSetting = $this->repository->getById($imageSettingId);
            $this->loadedData[$imageSettingId] = $imageSetting->getData();
            $this->loadedData[$imageSettingId][ImageSettingInterface::FOLDERS] = $imageSetting->getFolders();
        }
        $data = $this->dataPersistor->get(RegistryConstants::IMAGE_SETTING_DATA);

        if (!empty($data)) {
            $imageSettingId = isset($data[RegistryConstants::IMAGE_SETTING_ID])
                ? $data[RegistryConstants::IMAGE_SETTING_ID]
                : null;
            $this->loadedData[$imageSettingId] = $data;
            $this->dataPersistor->clear(RegistryConstants::IMAGE_SETTING_DATA);
        }

        return $this->loadedData;
    }

    public function getMeta()
    {
        $meta = parent::getMeta();
        $imageSettingId = 0;
        $imageSettingCollection = $this->repository->getImageSettingCollection();
        if ($imageSettingId = $this->request->getParam(RegistryConstants::IMAGE_SETTING_ID)) {
            $imageSettingCollection->addFieldToFilter(
                ImageSettingInterface::IMAGE_SETTING_ID,
                ['neq' => (int)$imageSettingId]
            );
        }
        $this->excludeFolders = [];
        /** @var \Amasty\PageSpeedOptimizer\Api\Data\ImageSettingInterface $item */
        foreach ($imageSettingCollection->getItems() as $item) {
            //phpcs:ignore
            $this->excludeFolders = array_merge($this->excludeFolders, $item->getFolders());
        }
        $result = [];
        $this->getFolders('.', 0, $result);
        $meta['general']['children']['folders']['arguments']['data']['options'] = $result;

        if ($this->dataPersistor->get(RegistryConstants::OPTIMIZE) && $imageSettingId) {
            $meta['modal']['children']['optimization']['arguments']['data']['config'] = [
                'forceStart' => 1,
                'startUrl' => $this->url->getUrl(
                    'amoptimizer/image/start',
                    [RegistryConstants::IMAGE_SETTING_ID => $imageSettingId]
                ),
                'processUrl' => $this->url->getUrl(
                    'amoptimizer/image/process',
                    [RegistryConstants::IMAGE_SETTING_ID => $imageSettingId]
                )
            ];
            $this->dataPersistor->clear(RegistryConstants::OPTIMIZE);
        }

        return $meta;
    }

    public function getFolders($path, $level, &$result)
    {
        $folders = $this->mediaDirectory->read($path);
        foreach ($folders as $folder) {
            if ($this->mediaDirectory->isDirectory($folder)) {
                $folder = preg_replace('/^\.\/(.*)/is', '$1', $folder);
                if ($level < 3) {
                    $result[] = [
                        'label' => $folder,
                        'value' => $folder,
                        'optgroup' => [],
                        'disabled' => in_array($folder, $this->excludeFolders)
                    ];
                    $this->getFolders(
                        $folder,
                        $level + 1,
                        $result[count($result)-1]['optgroup']
                    );
                    if (empty($result[count($result)-1]['optgroup'])) {
                        unset($result[count($result)-1]['optgroup']);
                    }
                }
            }
        }
    }
}
