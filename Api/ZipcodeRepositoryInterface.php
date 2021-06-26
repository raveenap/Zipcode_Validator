<?php

namespace Codilar\Zipcode\Api;

use Codilar\Zipcode\Model\ResourceModel\Zipcode\Collection;
use Codilar\Zipcode\Model\Zipcode as Model;

/**
 * Interface VendorRepositoryInterface
 * @package Codilar\Zipcode\Api
 *
 */
interface ZipcodeRepositoryInterface
{
    /**
     * @param $id
     * @return Model
     */
    public function getDataBYId($id);

    /**
     * @param Model $model
     * @return Model
     */
    public function save(Model $model);

    /**
     * @param Model $model
     * @return Model
     */
    public function afterSave(Model $model);


    /**
     * @param Model $model
     * @return Model
     */
    public function delete(Model $model);

    /**
     * @param $value
     * @param null $field
     * @return Model
     */
    public function load($value, $field = null);

    /**
     * @return Model $model
     */
    public function create();

    /**
     * @param int $id
     * @return Model
     */
    public function deleteById($id);

    /**
     * @return Collection
     */
    public function getCollection();

    /**
     * @return boolean
     */
    public function deleteByField($value, $field=null);

}
