<?php

namespace Codilar\Zipcode\Model\ResourceModel\Zipcodes;

use Codilar\Zipcode\Model\Zipcodes as Model;
use Codilar\Zipcode\Model\ResourceModel\Zipcodes as ResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
/**
 * class Collection
 * @link     https://www.codilar.com
 * @copyright Copyright © 2021 Codilar Technologies Pvt. Ltd.. All rights reserved
 *
 */
class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}
