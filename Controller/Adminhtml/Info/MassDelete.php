<?php

namespace Codilar\Zipcode\Controller\Adminhtml\Info;

use Codilar\Zipcode\Api\ZipcodeRepositoryInterface;
use Magento\Backend\Model\UrlInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;

/**
 * Class MassDelete
 * @package Codilar\Zipcode\Controller\Adminhtml\Info
 */
class MassDelete extends \Magento\Backend\App\Action
{

    /**
     * @var Filter
     */
    protected $filter;

    protected $zipcodeRepository;
    private  $request;
    private $url;

    /**
     * @param Context $context
     * @param Filter $filter
     *
     */
    public function __construct(
        Context $context,
        Filter $filter,
        RequestInterface $request,
        ZipcodeRepositoryInterface $zipcodeRepository,
        UrlInterface $url
    )
    {
        $this->filter = $filter;
        parent::__construct($context);
        $this->zipcodeRepository = $zipcodeRepository;
        $this->request = $request;
        $this->url = $url;
    }
    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     * @throws \Magento\Framework\Exception\LocalizedException|\Exception
     */
    public function execute()
    {
        $deleteIds = $this->getRequest()->getParams('selected');

        $collection = $this->zipcodeRepository->getCollection();

        $collection->addFieldToFilter('id', array('in' => $deleteIds));

        $count = 0;
        foreach ($collection as $child) {
            $child->delete();
            $count++;
        }

        $this->messageManager->addSuccessMessage(__(' record have been deleted'));

        $redirectResponse = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $redirectResponse->setPath('zipcode/info/index');
        return $redirectResponse;

    }
}
