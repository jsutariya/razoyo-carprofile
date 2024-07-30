<?php

declare(strict_types=1);

namespace Razoyo\CarProfile\Block\Mycar;

use Razoyo\CarProfile\Helper\Data;

class Get extends \Magento\Framework\View\Element\Template
{
    /**
     * @var Data
     */
    private $razoyoHelper;

    /**
     * Constructor
     *
     * @param \Magento\Framework\View\Element\Template\Context  $context
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        Data $razoyoHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->razoyoHelper = $razoyoHelper;
    }


    public function getAllCars() {
        return $this->razoyoHelper->getCars();
    }
}

