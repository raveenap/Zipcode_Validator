<?php

namespace Codilar\Zipcode\Controller\Result;

use Codilar\Zipcode\Model\Api\ZipcodesRepository;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;

/**
 * Class Result
 * @package Codilar\Zipcode\Controller\Result
 */
class Result extends Action
{
    /**
     * @var ZipcodesRepository
     */
    private $zipcodesRepository;

    /**
     * ZipChecker constructor.
     * @param Context $context
     * @param ZipcodesRepository $zipcodesRepository
     */

    public function __construct(
        Context $context,
        ZipcodesRepository $zipcodesRepository
    ) {
        parent::__construct($context);
        $this->zipcodesRepository = $zipcodesRepository;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\ResultInterface|void
     */
    public function execute()
    {

        $response = [];
        try {
            if (!$this->getRequest()->isAjax()) {
                throw new \Exception("Invalid Request. Try again.");
            }

            if (!$zipcode = $this->getRequest()->getParam('zipcode')) {
                throw new \Exception("Pease enter zipcode");
            }
            $collections = $this->zipcodesRepository->getCollection();
            foreach ($collections as $collection) {
                $check = $collection->getId();
                if ($zipcode == $check) {
                    $response['type'] = 'success';
                    $response['message'] = 'product deliverable';
                    break;
                }
            }
            $response['type'] = 'error';
            $response['message'] = 'product not deliverable';
        }catch (\Exception $e) {
            $response['type'] = 'error';
            $response['message'] = $e->getMessage();
        }
        $this->getResponse()->setContent(json_encode($response));
    }
}
