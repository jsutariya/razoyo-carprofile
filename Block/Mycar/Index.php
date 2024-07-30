<?php

declare(strict_types=1);

namespace Razoyo\CarProfile\Block\Mycar;

use Magento\Customer\Model\SessionFactory;
use Magento\Framework\View\Element\Template\Context;
use Razoyo\CarProfile\Helper\Data;
use Magento\Customer\Api\CustomerRepositoryInterface;

class Index extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Data
     */
    private $razoyoHelper;
    /**
     * @var SessionFactory
     */
    private $session;
    /**
     * @var CustomerRepositoryInterface
     */
    private $customerRepository;

    /**
     * Constructor
     *
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Context $context,
        SessionFactory $session,
        CustomerRepositoryInterface $customerRepository,
        Data $razoyoHelper,
        array $data = []
    )
    {
        parent::__construct($context, $data);
        $this->razoyoHelper = $razoyoHelper;
        $this->session = $session;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @return array|bool|bool[]|mixed
     */
    public function getMyCar()
    {
        $customerSession = $this->session->create();
        if($customerSession->isLoggedIn()) {
            $customer = $this->customerRepository->getById($customerSession->getCustomer()->getId());
            $savedCar = $customer->getCustomAttribute('saved_car');
            if($savedCar->getValue()) {
                return $this->razoyoHelper->getCarDetails($savedCar->getValue());
            }
        }
        return false;
    }
}

