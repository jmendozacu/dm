<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PageSpeedOptimizer
 */


namespace Amasty\PageSpeedOptimizer\Controller\Adminhtml\Image;

use Amasty\PageSpeedOptimizer\Controller\Adminhtml\AbstractImageSettings;
use Amasty\PageSpeedOptimizer\Controller\Adminhtml\RegistryConstants;
use Amasty\PageSpeedOptimizer\Api\ImageSettingRepositoryInterface;
use Amasty\PageSpeedOptimizer\Model\OptionSource\GifOptimization;
use Amasty\PageSpeedOptimizer\Model\OptionSource\JpegOptimization;
use Amasty\PageSpeedOptimizer\Model\OptionSource\PngOptimization;
use Amasty\PageSpeedOptimizer\Model\OptionSource\WebpOptimization;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class Save
 */
class Save extends AbstractImageSettings
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
     * @var bool
     */
    private $execChecked = false;

    public function __construct(
        Context $context,
        ImageSettingRepositoryInterface $repository,
        DataPersistorInterface $dataPersistor
    ) {
        parent::__construct($context);
        $this->repository = $repository;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        if ($data = $this->getRequest()->getParams()) {
            try {
                $imageSettingId = 0;
                if ($imageSettingId = (int)$this->getRequest()->getParam(RegistryConstants::IMAGE_SETTING_ID)) {
                    $model = $this->repository->getById($imageSettingId);
                } else {
                    $model = $this->repository->getEmptyImageSettingModel();
                }

                $model->addData($data);
                $this->checkTools($model);
                $model->setFolders($model->getFolders());
                $this->repository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the item.'));

                if ($this->getRequest()->getParam('back')) {
                    return $this->_redirect('*/*/edit', [RegistryConstants::IMAGE_SETTING_ID => $model->getId()]);
                }

                if ($this->getRequest()->getParam('save_and_optimize')) {
                    $this->dataPersistor->set(RegistryConstants::OPTIMIZE, true);
                    return $this->_redirect(
                        '*/*/edit',
                        [RegistryConstants::IMAGE_SETTING_ID => $model->getId()]
                    );
                }
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->dataPersistor->set(RegistryConstants::IMAGE_SETTING_DATA, $data);
                if ($imageSettingId) {
                    return $this->_redirect('*/*/edit', [RegistryConstants::IMAGE_SETTING_ID => $imageSettingId]);
                } else {
                    return $this->_redirect('*/*/create');
                }
            }
        }
        return $this->_redirect('*/*/');
    }

    /**
     * @param \Amasty\PageSpeedOptimizer\Api\Data\ImageSettingInterface $model
     */
    public function checkTools($model)
    {
        if ($model->getJpegTool()) {
            $this->checkCommand(JpegOptimization::TOOLS[$model->getJpegTool()]);
        }

        if ($model->getPngTool()) {
            $this->checkCommand(PngOptimization::TOOLS[$model->getPngTool()]);
        }

        if ($model->getGifTool()) {
            $this->checkCommand(GifOptimization::TOOLS[$model->getGifTool()]);
        }

        if ($model->isCreateWebp()) {
            $this->checkCommand(WebpOptimization::WEBP);
        }
    }

    /**
     * @param $command
     *
     * @return void
     */
    private function checkCommand($command)
    {
        $disabled = explode(',', str_replace(' ', ',', ini_get('disable_functions')));
        if (in_array('exec', $disabled) && !$this->execChecked) {
            if (!$this->execChecked) {
                $this->execChecked = true;
                $this->messageManager->addWarningMessage(__('exec function is disabled.'));
            }

            return;
        }

        if (empty($command['check']) || empty($command['check']['command']) || empty($command['check']['result'])) {
            return;
        }

        $output = [];
        /** @codingStandardsIgnoreStart */
        exec($command['check']['command'] . ' 2>&1', $output);
        /** @codingStandardsIgnoreEnd */
        if (!empty($output)) {
            foreach ($output as $line) {
                if (false !== strpos($line, $command['check']['result'])) {
                    return;
                }
            }
        }

        $this->messageManager->addWarningMessage(__('Image Optimization Tool "%1" is not installed', $command['name']));
    }
}
