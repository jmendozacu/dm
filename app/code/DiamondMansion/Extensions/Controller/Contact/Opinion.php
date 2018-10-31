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

class Opinion extends \Magento\Contact\Controller\Index\Post
{
    const XML_PATH_EMAIL_RECIPIENT = 'diamondmansion/email/opinion';

    protected $_contactFactory;
    protected $_transportBuilder;
    protected $_storeManager;
    protected $_scopeConfig;

    public function __construct(
        \DiamondMansion\Extensions\Model\Contact\OpinionFactory $contactFactory,
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
            return $this->resultRedirectFactory->create()->setPath('*/*/');
        }
        try {
            $params = $this->validatedParams();
            $sender = [
                'name' => $params['name'],
                'email' => $params['email']
            ];

            $storeId = $this->_storeManager->getStore()->getId();
            $transport = $this->_transportBuilder->setTemplateIdentifier('modulename_test_template')
                ->setTemplateOptions(['area' => 'frontend', 'store' => $storeId])
                ->setTemplateVars(
                    [
                        'store' => $this->_storeManager->getStore(),
                    ]
                )
                ->setFrom($sender)
                // you can config general email address in Store -> Configuration -> General -> Store Email Addresses
                ->addTo($this->_scopeConfig->getValue(self::XML_PATH_EMAIL_RECIPIENT, \Magento\Store\Model\ScopeInterface::SCOPE_STORE))
                ->getTransport();
            $transport->sendMessage();

            $this->messageManager->addSuccessMessage(
                __('Thanks for contacting us with your comments and questions. We\'ll respond to you very soon.')
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
        return $this->resultRedirectFactory->create()->setPath('contact/index');
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
            throw new LocalizedException(__('Comment is missing'));
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
