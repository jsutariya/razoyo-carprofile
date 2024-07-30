<?php

declare(strict_types=1);

namespace Razoyo\CarProfile\Controller\Mycar;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\View\LayoutFactory;
use Magento\Framework\Controller\ResultFactory;

class Get implements HttpGetActionInterface
{

    /**
     * @var PageFactory
     */
    protected $resultPageFactory;
    /**
     * @var Context
     */
    private $templateContext;
    /**
     * @var LayoutFactory
     */
    private $layoutFactory;
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
        ResultFactory $resultFactory,
        Context $templateContext,
        LayoutFactory $layoutFactory
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->templateContext = $templateContext;
        $this->layoutFactory = $layoutFactory;
        $this->resultFactory = $resultFactory;
    }

    /**
     * Execute view action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $layout = $this->layoutFactory->create();
        $block = $layout->createBlock("Razoyo\CarProfile\Block\Mycar\Get")->setTemplate("Razoyo_CarProfile::mycar/get.phtml");

        $html = $block->toHtml();

        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData(['success' => true,'html' => $html]);
        return $resultJson;
    }
}

