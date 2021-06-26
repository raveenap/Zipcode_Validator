<?php

namespace Codilar\Zipcode\Controller\Result;

use Codilar\Zipcode\Model\Api\ZipcodesRepository;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Controller\ResultInterface;

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
    private $productRepository;

    /**
     * ZipChecker constructor.
     * @param Context $context
     * @param ZipcodesRepository $zipcodesRepository
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        Context $context,
        ZipcodesRepository $zipcodesRepository,
        ProductRepositoryInterface $productRepository
    ) {
        parent::__construct($context);
        $this->zipcodesRepository = $zipcodesRepository;
        $this->productRepository = $productRepository;
    }

    /**
     * @return ResponseInterface|ResultInterface|void
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
            $zipCode = $this->getRequest()->getParam('zipcode');
            $productId = $this->getRequest()->getParam('product_id');
            $product = $this->productRepository->getById($productId);
            $attribute = $product->getCustomAttribute('region_zipcodes');
            $response['type'] = 'error';
            $response['message'] = 'this product doesn\'t available for deliver';
            if(isset($attribute)) {
                $zipCodeRegions = $attribute->getValue()?explode(',', $attribute->getValue()):'';
                $collections = $this->zipcodesRepository->getCollection();
                $collections->addFieldToFilter('region_id', ['in'=>$zipCodeRegions]);
                $collections->addFieldToFilter('zipcode', $zipCode);
               if(count($collections->getData())){
                   $collection = $collections->getFirstItem();
                   $response['type'] = 'success';
                   $response['message'] = sprintf("The Product is Available to your current Location, and it will take %s", $collection->getTimeDeliver());
               }
            } else {
                $response['type'] = 'error';
                $response['message'] = 'this product doesn\'t available for deliver';
            }
        }catch (\Exception $e) {
            $response['type'] = 'error';
            $response['message'] = $e->getMessage();
        }
        $this->getResponse()->setContent(json_encode($response));
    }
}
