<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PageSpeedOptimizer
 */


namespace Amasty\PageSpeedOptimizer\Model\Image;

use Amasty\PageSpeedOptimizer\Api\Data\ImageSettingInterface;
use Amasty\PageSpeedOptimizer\Model\OptionSource\Resolutions;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * Class GenerateQueue
 *
 * @package Amasty\PageSpeedOptimizer
 */
class GenerateQueue
{
    /**
     * @var \Amasty\PageSpeedOptimizer\Model\Queue\QueueRepository
     */
    private $queueRepository;

    /**
     * @var \Magento\Framework\Filesystem\Directory\ReadInterface
     */
    private $mediaDirectory;

    /**
     * @var \Amasty\PageSpeedOptimizer\Model\ConfigProvider
     */
    private $configProvider;

    /**
     * @var \Amasty\PageSpeedOptimizer\Model\Queue\QueueFactory
     */
    private $queueFactory;

    /**
     * @var ResourceModel\CollectionFactory
     */
    private $collectionFactory;

    public function __construct(
        \Amasty\PageSpeedOptimizer\Api\QueueRepositoryInterface $queueRepository,
        \Amasty\PageSpeedOptimizer\Model\Queue\QueueFactory $queueFactory,
        \Magento\Framework\Filesystem $filesystem,
        \Amasty\PageSpeedOptimizer\Model\ConfigProvider $configProvider,
        \Amasty\PageSpeedOptimizer\Model\Image\ResourceModel\CollectionFactory $collectionFactory
    ) {
        $this->queueRepository = $queueRepository;
        $this->mediaDirectory = $filesystem->getDirectoryRead(DirectoryList::MEDIA);
        $this->configProvider = $configProvider;
        $this->queueFactory = $queueFactory;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @param null|int $imageSettingId
     *
     * @return int
     */
    public function generateQueue($imageSettingId = null)
    {
        $this->queueRepository->clearQueue();
        $this->processFiles($imageSettingId);

        return $this->queueRepository->getQueueSize();
    }

    /**
     * @param null|int $imageSettingId
     *
     * @return void
     */
    public function processFiles($imageSettingId)
    {
        $imageFolders = $this->prepareFolders($imageSettingId);
        foreach ($imageFolders as $imageDirectory => $imageSetting) {
            $files = $this->mediaDirectory->readRecursively($imageDirectory);
            foreach ($files as $file) {
                $skip = false;
                foreach (Resolutions::RESOLUTIONS as $resolution) {
                    if (strpos($file, $resolution['dir']) !== false) {
                        $skip = true;
                    }
                }
                if (!$skip && strpos($file, Process::DUMP_DIRECTORY) === false
                    && $this->mediaDirectory->isFile($file)
                ) {
                    /** @codingStandardsIgnoreStart */
                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    /** @codingStandardsIgnoreEnd */
                    switch ($ext) {
                        case 'jpg':
                        case 'jpeg':
                            $skip = !$imageSetting->getJpegTool() && !$imageSetting->isCreateWebp();
                            $tool = $imageSetting->getJpegTool();
                            break;
                        case 'png':
                            $skip = !$imageSetting->getPngTool() && !$imageSetting->isCreateWebp();
                            $tool = $imageSetting->getPngTool();
                            break;
                        case 'gif':
                            $skip = !$imageSetting->getGifTool() && !$imageSetting->isCreateWebp();
                            $tool = $imageSetting->getGifTool();
                            break;
                        default:
                            $skip = true;
                    }
                    //phpcs:ignore
                    $dir = pathinfo($file, PATHINFO_DIRNAME);
                    if ($dir !== $imageDirectory && isset($imageFolders[$dir])) {
                        $skip = true;
                    }
                    if ($skip) {
                        continue;
                    }
                    $resolutions = [];
                    if ($imageSetting->isCreateMobileResolution()) {
                        $resolutions[] = Resolutions::MOBILE;
                    }
                    if ($imageSetting->isCreateTabletResolution()) {
                        $resolutions[] = Resolutions::TABLET;
                    }
                    /** @var \Amasty\PageSpeedOptimizer\Api\Data\QueueInterface $queue */
                    $queue = $this->queueFactory->create();
                    $queue->setFilename($file)
                        ->setExtension($ext)
                        ->setResolutions($resolutions)
                        ->setTool($tool)
                        ->setIsUseWebP($imageSetting->isCreateWebp())
                        ->setIsDumpOriginal($imageSetting->isDumpOriginal())
                        ->setResizeAlgorithm($imageSetting->getResizeAlgorithm());

                    $this->queueRepository->addToQueue($queue);
                }
            }
        }
    }

    /**
     * @param int|null $imageSettingId
     *
     * @return \Amasty\PageSpeedOptimizer\Api\Data\ImageSettingInterface[];
     */
    public function prepareFolders($imageSettingId)
    {
        $imageSettingCollecion = $this->collectionFactory->create();
        $imageSettingCollecion->addFieldToFilter(ImageSettingInterface::IS_ENABLED, 1);
        if ($imageSettingId) {
            $imageSettingCollecion->addFieldToFilter(ImageSettingInterface::IMAGE_SETTING_ID, (int)$imageSettingId);
        }
        $folders = [];
        /** @var \Amasty\PageSpeedOptimizer\Api\Data\ImageSettingInterface $item */
        foreach ($imageSettingCollecion->getItems() as $item) {
            foreach ($item->getFolders() as $folder) {
                $folders[$folder] = $item;
            }
        }

        return $folders;
    }
}
