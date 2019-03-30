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
     * @param \Magento\Framework\App\ActionFactory $actionFactory
     * @param \Magento\Framework\App\ResponseInterface $response
     */
    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Magento\Framework\App\ResponseInterface $response,
        \Magenest\InstagramShop\Model\Client $client
    ) {
        $this->actionFactory = $actionFactory;
        $this->_response = $response;
        $this->tags = $client->getTags();
    }

    /**
     * Validate and Match Instagram Gallery page and modify request
     *
     * @param \Magento\Framework\App\RequestInterface $request
     * @return bool
     */
    public function match(\Magento\Framework\App\RequestInterface $request)
    {
        $tags = $this->tags;

        if (is_null($tags)) {
            return null;
        }

        $_identifier = trim($request->getPathInfo(), '/');

        if (strpos($_identifier, 'instagram') !== 0) {
            return null;
        }

        $identifier = str_replace(['instagram/', 'instagram'], '', $_identifier);

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
