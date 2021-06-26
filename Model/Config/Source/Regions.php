<?php

namespace Codilar\Zipcode\Model\Config\Source;

use Codilar\Zipcode\Model\ResourceModel\Zipcode\Collection;
use Codilar\Zipcode\Model\ResourceModel\Zipcode\CollectionFactory;

/**
 * Class Regions
 * @package Codilar\Zipcode\Model\Config\Source\Zipcode
 */
class Regions extends \Magento\Eav\Model\Entity\Attribute\Source\AbstractSource
{
    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;

    /**
     * Regions constructor.
     * @param CollectionFactory $collectionFactory
     */
    public function __construct(
        CollectionFactory $collectionFactory
    )
    {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @param bool $addEmpty
     * @return array
     */
    public function getAllOptions($addEmpty = true): array
    {

        /** @var Collection $collection */
        $collections = $this->collectionFactory->create();
        $result = [];
        $result[] = [
            'label' => '-----Select-----',
            'value' => ''
        ];
        foreach ($collections as $collection) {
            if ($collection->getData('isEnable')) {
                $result[] = [
                    'label' => $collection->getRegion(),
                    'value' => $collection->getId()
                ];
            }
        }
        return $result;


    }
}
