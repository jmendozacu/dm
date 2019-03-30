<?php
/**
 *
  * Copyright © 2018 Magenest. All rights reserved.
  * See COPYING.txt for license details.
  *
  * Magenest_InstagramShop extension
  * NOTICE OF LICENSE
  *
  * @category Magenest
  * @package  Magenest_InstagramShop
  * @author    dangnh@magenest.com

 */

namespace Magenest\InstagramShop\Ui\Component\Listing\Column;

use Magenest\InstagramShop\Model\Photo;

/**
 * Class PhotoHotspotActions
 * @package Magenest\InstagramShop\Ui\Component\Listing\Column
 */
class PhotoHotspotActions extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;


    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    )
    {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     * @throws \Exception
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['id'])) {
                    $url                          = $this->urlBuilder->getUrl('instagram/instagram/redirectLink', [
                        'type' => 'hotspot',
                        'photo_id' => $item['photo_id'],
                        'photo_type' => Photo::TYPE
                    ]);
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $url,
                            'label' => __('Create/Edit Hotspot'),
                            'target' => '_blank'
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
