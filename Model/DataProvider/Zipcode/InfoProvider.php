<?php

namespace Codilar\Zipcode\Model\DataProvider\Zipcode;

use Codilar\Zipcode\Model\ResourceModel\Zipcode\Collection as Collection;
use Codilar\Zipcode\Model\ResourceModel\Zipcode\CollectionFactory as CollectionFactory;
use Magento\Framework\App\RequestInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

/**
 * Class InfoProvider
 * @package Codilar\Zipcode\Model\DataProvider\Zipcode
 */
class InfoProvider extends AbstractDataProvider
{
    /**
     * @var
     */
    protected $loadedData;
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var Collection
     */
    private $collectionFactory;

    /**
     * DataProvider constructor.
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param RequestInterface $request
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->request = $request;
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $id = $this->request->getParam('id');
        $items = $this->collectionFactory->create()->addFieldToFilter('id', $id)->getItems();
        foreach ($items as $item) {
            $sellerData = $item->getData();
            $this->loadedData[$item->getId()] = $sellerData;
        }
        return $this->loadedData;
    }
}
