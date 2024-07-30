<?php

declare(strict_types=1);

namespace Razoyo\CarProfile\Controller\Mycar;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Customer\Model\Session;
use Magento\Framework\UrlInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Controller\ResultFactory;

class Index implements HttpGetActionInterface
{

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var Session
     */
    private $customerSession;
    /**
     * @var UrlInterface
     */
    private $urlInterface;
    /**
     * @var ManagerInterface
     */
    private $messageManager;
    /**
     * @var ResultFactory
     */
    private $resultFactory;

    /**
     * Constructor
     *
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        PageFactory $resultPageFactory,
        Session $customerSession,
        UrlInterface $urlInterface,
        ManagerInterface $messageManager,
        ResultFactory $resultFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->customerSession = $customerSession;
        $this->urlInterface = $urlInterface;
        $this->messageManager = $messageManager;
        $this->resultFactory = $resultFactory;
    }

    /**
     * Execute view action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        if (!$this->customerSession->isLoggedIn()) {
            $currentUrl = $this->urlInterface->getCurrentUrl();
            $login_url = $this->urlInterface
                ->getUrl('customer/account/login',
                    array('referer' => base64_encode($currentUrl))
                );
            $this->messageManager->addErrorMessage(__('You must be logged in to access this page.'));
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setPath($login_url);
            return $resultRedirect;
        }
        return $this->resultPageFactory->create();
    }
}

