<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Razoyo\CarProfile\Controller\Mycar;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Response\Http;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;
use Magento\Customer\Model\Session;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\RequestInterface;

class Save implements HttpPostActionInterface
{

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var Json
     */
    protected $serializer;
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var Http
     */
    protected $http;
    /**
     * @var Session
     */
    private $customerSession;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * Constructor
     *
     * @param PageFactory $resultPageFactory
     * @param Json $json
     * @param LoggerInterface $logger
     * @param Http $http
     */
    public function __construct(
        PageFactory $resultPageFactory,
        Json $json,
        LoggerInterface $logger,
        Http $http,
        Session $customerSession,
        CustomerRepositoryInterface $customerRepository,
        RequestInterface $request
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->serializer = $json;
        $this->logger = $logger;
        $this->http = $http;

        $this->customerSession = $customerSession;
        $this->customerRepository = $customerRepository;
        $this->request = $request;
    }

    /**
     * Execute view action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $response = [];
        try {
            $customerId = $this->customerSession->getCustomerId();
            $savedCar = $this->request->getParam('car_id');

            if ($customerId) {
                try {
                    $customer = $this->customerRepository->getById($customerId);
                    $customer->setCustomAttribute('saved_car', $savedCar);
                    $this->customerRepository->save($customer);
                    $response['message'] = __('Saved car updated successfully.');
                    $response['success'] = true;
                } catch (\Exception $e) {
                    $response['success'] = false;
                    $response['message'] = __('Error saving car: ' . $e->getMessage());
                }
            } else {
                $response['success'] = false;
                $response['message'] = __('Customer not logged in.');
            }
            return $this->jsonResponse($response);
        } catch (LocalizedException $e) {
            return $this->jsonResponse($e->getMessage());
        } catch (\Exception $e) {
            $this->logger->critical($e);
            return $this->jsonResponse($e->getMessage());
        }
    }

    /**
     * Create json response
     *
     * @return ResultInterface
     */
    public function jsonResponse($response = '')
    {
        $this->http->getHeaders()->clearHeaders();
        $this->http->setHeader('Content-Type', 'application/json');
        return $this->http->setBody(
            $this->serializer->serialize($response)
        );
    }
}

