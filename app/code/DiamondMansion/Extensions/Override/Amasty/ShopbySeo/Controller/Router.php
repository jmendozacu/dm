<?php
namespace DiamondMansion\Extensions\Override\Amasty\ShopbySeo\Controller;

use \Amasty\ShopbySeo\Helper\Url;
use \Amasty\ShopbySeo\Helper\UrlParser;
use \Magento\Framework\App\RequestInterface;
use \Magento\UrlRewrite\Model\UrlFinderInterface;
use \Magento\UrlRewrite\Service\V1\Data\UrlRewrite;
use \Magento\Framework\Module\Manager;
use \Magento\Store\Model\ScopeInterface;

class Router extends \Amasty\ShopbySeo\Controller\Router {
    const INDEX_ALIAS       = 1;
    const INDEX_CATEGORY    = 2;

    /**
     * @var \Magento\Framework\App\ActionFactory
     */
    protected $actionFactory;

    /**
     * @var \Magento\Framework\App\ResponseInterface
     */
    protected $_response;

    /**
     * @var Url
     */
    protected $urlHelper;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var UrlParser
     */
    protected $urlParser;

    /**
     * @var UrlFinderInterface
     */
    protected $urlFinder;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var Manager
     */
    protected $moduleManager;
    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    /**
     * @var \Amasty\ShopbySeo\Helper\Data
     */
    private $helper;

    /**
     * @var \Amasty\ShopbyBase\Model\AllowedRoute
     */
    private $allowedRoute;

    public function __construct(
        \Magento\Framework\App\ActionFactory $actionFactory,
        \Magento\Framework\App\ResponseInterface $response,
        \Magento\Framework\Registry $registry,
        \Amasty\ShopbyBase\Model\AllowedRoute $allowedRoute,
        UrlParser $urlParser,
        Url $urlHelper,
        UrlFinderInterface $urlFinder,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Psr\Log\LoggerInterface $logger,
        Manager $moduleManager,
        \Amasty\ShopbySeo\Helper\Data $helper
    ) {
        $this->actionFactory = $actionFactory;
        $this->_response = $response;
        $this->registry = $registry;
        $this->urlHelper = $urlHelper;
        $this->urlParser = $urlParser;
        $this->urlFinder = $urlFinder;
        $this->scopeConfig = $scopeConfig;
        $this->moduleManager = $moduleManager;
        $this->logger = $logger;
        $this->helper = $helper;
        $this->allowedRoute = $allowedRoute;
    }

    /**
     * @param RequestInterface $request
     * @return bool|\Magento\Framework\App\ActionInterface
     */
    public function match(RequestInterface $request)
    {
        $params = $request->getParams();
        
        $identifier = trim($request->getPathInfo(), '/');

        $identifier .= isset($params['band']) ? '/' . $params['band'] : '';
        $identifier .= isset($params['design-collection']) ? '/' . $params['design-collection'] : '';
        $identifier .= isset($params['metal']) ? '/' . $params['metal'] : '';

        $brandUrlKey = $this->helper->getBrandUrlKey();
        $positionBrandUrlKey = $brandUrlKey ? strpos($identifier, $brandUrlKey) : false;

        if ($positionBrandUrlKey !== false) {
            $matches[self::INDEX_ALIAS] = substr($identifier, 0, $positionBrandUrlKey + iconv_strlen($brandUrlKey));
            $matches[self::INDEX_CATEGORY] = substr($identifier, $positionBrandUrlKey);
        } else {
            /* -- Custom Code for DM -- */
            $rewrite = false;
            $category = $identifier;
            $posLastValue = true;
            while (!$rewrite && $posLastValue !== false) {
                $posLastValue = strrpos($category, "/");
                $category = substr($category, 0, $posLastValue);

                $rewrite = $this->urlFinder->findOneByData([
                    UrlRewrite::REQUEST_PATH => $category,
                ]);
            }

            $matches[self::INDEX_ALIAS] = $category;
            $matches[self::INDEX_CATEGORY] = substr($identifier, strlen($category) + 1);
            
            //$posLastValue = strrpos($identifier, "/");
            //$matches[self::INDEX_ALIAS] = substr($identifier, 0, $posLastValue);
            //$positionFrom = ($posLastValue === false) ? 0 : $posLastValue + 1;
            //$matches[self::INDEX_CATEGORY] = substr($identifier, $positionFrom);
            /* -- Custom Code for DM End -- */
        }

        $seoPart = $this->urlHelper->removeCategorySuffix($matches[self::INDEX_CATEGORY]);

        $suffix = $this->scopeConfig
            ->getValue('catalog/seo/category_url_suffix', ScopeInterface::SCOPE_STORE);
        $suffixMoved = $seoPart != $matches[self::INDEX_CATEGORY] || $suffix == '/';
        $regex = $this->helper->getFilterWord() ? '/\/+(' . $this->helper->getFilterWord() . ')/' : '';
        $alias = $regex ? preg_replace($regex, '', $matches[self::INDEX_ALIAS]) : $matches[self::INDEX_ALIAS];

        $params = $this->urlParser->parseSeoPart($seoPart);

        if ($params === false) {
            return false;
        }

        /**
         * for brand pages with key, e.g. /brand/adidas
         */
        $matchedAlias = null;

        /* For regular seo category */
        if (!$matchedAlias) {
            $category = $suffixMoved ? $alias . $suffix : $alias;
            $rewrite = $this->urlFinder->findOneByData([
                UrlRewrite::REQUEST_PATH => $category,
            ]);

            if ($rewrite) {
                $matchedAlias = $category;
            }
        }

        if ($matchedAlias) {
            $this->registry->unregister('amasty_shopby_seo_parsed_params');
            $this->registry->register('amasty_shopby_seo_parsed_params', $params);
            $request->setParams($params);
            /* -- Custom Code for DM -- */
            $request->setPathInfo(rtrim($matchedAlias, '/') . '/');
            /* -- Custom Code for DM End -- */
            return $this->actionFactory->create(\Magento\Framework\App\Action\Forward::class);
        }

        $this->registry->unregister('amasty_shopby_seo_parsed_params') ;
        $this->registry->register('amasty_shopby_seo_parsed_params', $params);

        if ($this->allowedRoute->isRouteAllowed($request)) {
            $request->setModuleName('amshopby')->setControllerName('index')->setActionName('index');
            $shopbyPageUrl = $this->scopeConfig->getValue('amshopby_root/general/url', ScopeInterface::SCOPE_STORE);
            $request->setAlias(\Magento\Framework\Url::REWRITE_REQUEST_PATH_ALIAS, $shopbyPageUrl);
            $params = array_merge($params, $request->getParams());
            $request->setParams($params);

            return $this->actionFactory->create(\Magento\Framework\App\Action\Forward::class);
        }

        return false;
    }

    /**
     * @param RequestInterface $request
     * @param bool $brandRedirect
     * @return bool|\Magento\Framework\App\ActionInterface
     */
    protected function createSeoRedirect(RequestInterface $request, $brandRedirect = false)
    {
        $url = $this->urlHelper->seofyUrl($request->getUri()->toString());
        if ($brandRedirect && $this->scopeConfig->isSetFlag('amasty_shopby_seo/url/add_suffix_shopby')) {
            $suffix = $this->scopeConfig->getValue('catalog/seo/category_url_suffix', ScopeInterface::SCOPE_STORE);
            if (strpos($url, '?') === false && substr($url, -strlen($suffix)) !== $suffix) {
                $url .= $suffix;
            }
        }

        if (strcmp($url, $request->getUri()->toString()) === 0) {
            return false;
        }

        $this->_response->setRedirect($url, \Zend\Http\Response::STATUS_CODE_301);
        $request->setDispatched(true);

        return $this->actionFactory->create(\Magento\Framework\App\Action\Redirect::class);
    }
}