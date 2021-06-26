<?php

namespace Codilar\Zipcode\Model;

use Magento\Framework\Model\AbstractModel;
use Codilar\Zipcode\Model\ResourceModel\Zipcode as ResourceModel;

/**
 * Class Zipcode
 * @package Codilar\Zipcode\Model
 */
class Zipcode extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(ResourceModel::class);
    }
}
