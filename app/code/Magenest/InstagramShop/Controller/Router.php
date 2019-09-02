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

namespace Magenest\InstagramShop\Controller;

/**
 * Instagram Controller Router
 */
class Router implements \Magento\Framework\App\RouterInterface
{
    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    protected $actionFactory;

    /**
     * Response
     *
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $_response;

    /**
     * @var array|null
     */
    protected $tags = null;

    /**
     * @var \Magenest\InstagramShop\Helper\Helper
     */
    protected $helper;

    /**
     * Router constructor.
     * @param \Magento\Framework\App\ActionFactory $actionFactory
     * @param \Magento\Framework\App\ResponseInterface $response
     * @param \Magenest\InstagramShop\Model\Client $client
     * @param \Magenest\InstagramShop\Helper\Helper $helper
     */
    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Magento\Framework\App\ResponseInterface $response,
        \Magenest\InstagramShop\Model\Client $client,
        \Magenest\InstagramShop\Helper\Helper $helper
    ) {
        $this->actionFactory = $actionFactory;
        $this->_response     = $response;
        $this->helper        = $helper;
        $this->tags          = $client->getTags();
    }

    /**
     * Validate and Match Instagram Gallery page and modify request
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @return \Magento\Framework\App\ActionInterface|null
     */
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        $tags = $this->tags;

        if (is_null($tags)) {
            return null;
        }

        $_identifier = trim($request->getPathInfo(), '/');

        $key = $this->helper->getGalleryUrl();
        if (strpos($_identifier, $key) !== 0) {
            return null;
        }

        $identifier = preg_replace('/\/+/', '', str_replace($key, '', $_identifier));

        $condition = new \Magento\Framework\DataObject(['identifier' => $identifier, 'continue' => true]);

        if ($condition->getRedirectUrl()) {
            $this->_response->setRedirect($condition->getRedirectUrl());
            $request->setDispatched(true);
            return $this->actionFactory->create(
                'Magento\Framework\App\Action\Redirect',
                ['request' => $request]
            );
        }

        if (!$condition->getContinue()) {
            return null;
        }

        $identifier = $condition->getIdentifier();

        $success = false;

        if (!$identifier) {
            $request->setModuleName('instagram')->setControllerName('gallery')->setActionName('index');
            $success = true;
        } else {
            foreach ($tags as $tag) {
                if ($identifier == $tag) {
                    $request->setModuleName('instagram')->setControllerName('gallery')->setActionName('index')->setParam('view', $tag);
                    $success = true;
                    break;
                }
            }
        }

        if (!$success) {
            return null;
        }

        $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $_identifier);

        return $this->actionFactory->create(
            'Magento\Framework\App\Action\Forward',
            ['request' => $request]
        );
    }
}
