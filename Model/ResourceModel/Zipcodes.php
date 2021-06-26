<?php

namespace Codilar\Zipcode\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

/**
 * Class Zipcode
 * @package Codilar\Zipcode\Model\ResourceModel
 */
class Zipcodes extends AbstractDb
{
    const MAIN_TABLE = 'codilar_region_zipcode';
    const ID_FIELD_NAME = 'zipcode_id';

    protected function _construct()
    {
        $this->_init(self::MAIN_TABLE, self::ID_FIELD_NAME);
    }
}
