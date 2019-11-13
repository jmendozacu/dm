<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2019 Amasty (https://www.amasty.com)
 * @package Amasty_PageSpeedOptimizer
 */


namespace Amasty\PageSpeedOptimizer\Controller\Adminhtml\Image;

use Amasty\PageSpeedOptimizer\Controller\Adminhtml\AbstractImageSettings;

/**
 * Class Create
 */
class Create extends AbstractImageSettings
{
    /**
     * @return void
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}
