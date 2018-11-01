<?php

namespace DiamondMansion\Extensions\Controller\Contact;


use \Magento\Contact\Model\ConfigInterface;
use \Magento\Contact\Model\MailInterface;
use \Magento\Framework\App\Action\Context;
use \Magento\Framework\App\Request\DataPersistorInterface;
use \Magento\Framework\Controller\Result\Redirect;
use \Magento\Framework\Exception\LocalizedException;
use \Magento\Framework\HTTP\PhpEnvironment\Request;
use \Psr\Log\LoggerInterface;
use \Magento\Framework\App\ObjectManager;
use \Magento\Framework\DataObject;

class Certificate extends \Magento\Contact\Controller\Index\Post
{
    protected $_contactFactory;
    protected $_transportBuilder;
    protected $_storeManager;
    protected $_scopeConfig;

    private $dataPersistor;
    private $logger;

    public function __construct(
        \DiamondMansion\Extensions\Model\Contact\CertificateFactory $contactFactory,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        Context $context,
        ConfigInterface $contactsConfig,
        MailInterface $mail,
        DataPersistorInterface $dataPersistor,
        LoggerInterface $logger = null
    ) {
        $this->_contactFactory = $contactFactory;
        $this->_transportBuilder = $transportBuilder;
        $this->_storeManager = $storeManager;
        $this->_scopeConfig = $scopeConfig;

        $this->dataPersistor = $dataPersistor;
        $this->logger = $logger ?: ObjectManager::getInstance()->get(LoggerInterface::class);

        parent::__construct($context, $contactsConfig, $mail, $dataPersistor, $logger);
    }
    /**
     * Post user question
     *
     * @return Redirect
     */
    public function execute()
    {
        if (!$this->isPostRequest()) {
            return $this->resultRedirectFactory->create()->setPath($this->_redirect->getRefererUrl());
        }
        try {
            $params = $this->validatedParams();
            $sender = [
                'name' => $params['name'],
                'email' => $params['email']
            ];

            $contact = $this->_contactFactory->create();
            $contact->setData([
                'name' => $params['name'],
                'email' => $params['email'],
                'phone' => $params['telephone'],
                'product_name' => $params['productname'],
                'product_link' => $params['productlink'],
                'product_price' => $params['productprice'],
                'message' => $params['comment']
            ])->save();

            $storeId = $this->_storeManager->getStore()->getId();

            $template = $this->_scopeConfig->getValue(
                'diamondmansion/email/certificate',
                \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
                $storeId
            );

            $transport = $this->_transportBuilder->setTemplateIdentifier($template)
                ->setTemplateOptions(['area' => 'frontend', 'store' => $storeId])
                ->setTemplateVars($params)
                ->setFrom($sender)
                // you can config general email address in Store -> Configuration -> General -> Store Email Addresses
                ->addTo($this->_scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT, \Magento\Store\Model\ScopeInterface::SCOPE_STORE))
                ->getTransport();
            $transport->sendMessage();

            $this->messageManager->addSuccessMessage(
                __('Your inquiry was submitted and will be responded to as soon as possible. Thank you for contacting us.')
            );
            $this->dataPersistor->clear('contact_us');
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->dataPersistor->set('contact_us', $this->getRequest()->getParams());
        } catch (\Exception $e) {
            $this->logger->critical($e);
            $this->messageManager->addErrorMessage(
                __('An error occurred while processing your form. Please try again later.')
            );
            $this->dataPersistor->set('contact_us', $this->getRequest()->getParams());
        }
        return $this->resultRedirectFactory->create()->setPath($this->_redirect->getRefererUrl());
    }

    /**
     * @return bool
     */
    private function isPostRequest()
    {
        /** @var Request $request */
        $request = $this->getRequest();
        return !empty($request->getPostValue());
    }


    /**
     * @return array
     * @throws \Exception
     */
    private function validatedParams()
    {
        $request = $this->getRequest();
        if (trim($request->getParam('name')) === '') {
            throw new LocalizedException(__('Name is missing'));
        }
        if (trim($request->getParam('comment')) === '') {
            throw new LocalizedException(__('Message is missing'));
        }
        if (false === \strpos($request->getParam('email'), '@')) {
            throw new LocalizedException(__('Invalid email address'));
        }
        if (trim($request->getParam('hideit')) !== '') {
            throw new \Exception();
        }

        return $request->getParams();
    }
}
