<?php

namespace Magenest\InstagramShop\Controller\Adminhtml\Instagram;

use Magenest\InstagramShop\Model\Client;
use Magento\Backend\App\Action;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Widget\Model\Widget\InstanceFactory;

/**
 * Class CreateWidget
 * @package Magenest\InstagramShop\Controller\Adminhtml\Instagram
 */
class CreateWidget extends Action
{
    /**
     * @var InstanceFactory
     */
    protected $_widgetInstanceFactory;

    /**
     * CreateWidget constructor.
     * @param Action\Context $context
     * @param InstanceFactory $factory
     */
    public function __construct(
        Action\Context $context,
        InstanceFactory $factory)
    {
        $this->_widgetInstanceFactory = $factory;
        parent::__construct($context);
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     */
    public function execute()
    {
        try {
            if (($themeId = $this->getRequest()->getParam('theme_id')) && ($layout = $this->getRequest()->getParam('layout'))) {
                $widget = $this->prepareWidgetData($layout, $themeId);
                $widget->save();
                $this->messageManager->addSuccessMessage(__('The widget instance has been saved.'));
            } else {
                throw new LocalizedException(__('You must choose a theme and a widget layout.'));
            }
            return $this->resultRedirectFactory->create()->setPath('adminhtml/widget_instance');
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            return $this->resultRedirectFactory->create()->setPath(Client::INSTAGRAM_SHOP_CONFIGURATION_SECTION);
        }
    }

    /**
     * @param $layout
     * @param $themeId
     * @return \Magento\Widget\Model\Widget\Instance
     * @throws LocalizedException
     */
    private function prepareWidgetData($layout, $themeId)
    {
        $itemWidth = '';
        $template  = 'slider/default.phtml';
        switch ($layout) {

            case 'default':
                $itemWidth = '200';
                break;
            case 'nine':
                $template = 'slider/grid-nine.phtml';
                break;
            case 'twelve':
                $template = 'slider/grid-twelve.phtml';
                break;
            default :
                throw new LocalizedException(__('Invalid selected layout'));
        }
        return $this->_widgetInstanceFactory->create()->setCode('instagram_photo_slider')
            ->setType('Magenest\InstagramShop\Block\Photo\Slider')
            ->setThemeId($themeId)
            ->setStoreIds([0])
            ->setWidgetParameters($this->getParameters($itemWidth))
            ->setSortOrder(0)
            ->setTitle('Instagram Shop')
            ->setData('page_groups', $this->getPageGroupsData($template));
    }

    /**
     * @param string $itemWidth
     * @return array
     */
    private function getParameters($itemWidth = '')
    {
        return array(
            'title'             => 'Hashtag us and be featured on our official Instagram',
            'direction'         => 'horizontal',
            'reverse'           => '0',
            'animation_loop'    => '1',
            'smooth_height'     => '0',
            'start_at'          => '',
            'slideshow'         => '1',
            'slide_show_speed'  => '',
            'animation_speed'   => '',
            'init_delay'        => '',
            'randomize'         => '0',
            'pause_on_action'   => '1',
            'pause_on_hover'    => '0',
            'useCSS'            => '1',
            'touch'             => '1',
            'video'             => '0',
            'control_nav'       => '1',
            'direction_nav'     => '1',
            'keyboard'          => '1',
            'multiple_keyboard' => '0',
            'mousewheel'        => '0',
            'pause_play'        => '0',
            'pause_text'        => '',
            'play_text'         => '',
            'item_width'        => $itemWidth,
            'item_margin'       => '',
            'min_items'         => '',
            'max_items'         => '',
            'move'              => '',
            'rtl'               => '0',
        );
    }

    /**
     * @param $template
     * @return array
     */
    private function getPageGroupsData($template)
    {
        return array(
            array(
                'page_group'   => 'pages',
                'pages'        =>
                    array(
                        'page_id'       => '22',
                        'for'           => 'all',
                        'layout_handle' => 'cms_index_index',
                        'block'         => 'content.top',
                        'template'      => $template,
                    ),
                'page_layouts' =>
                    array(
                        'layout_handle' => '',
                    ))
        );
    }
}