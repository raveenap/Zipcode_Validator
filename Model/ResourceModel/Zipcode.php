<?php

namespace Codilar\Zipcode\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Zipcode
 * @package Codilar\Zipcode\Model\ResourceModel
 */
class Zipcode extends AbstractDb
{
    const MAIN_TABLE = 'codilar_region';
    const ID_FIELD_NAME = 'id';

    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE, self::ID_FIELD_NAME);
    }
}
