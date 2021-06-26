<?php

namespace Codilar\Zipcode\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use phpDocumentor\Reflection\Types\Self_;

/**
 * Class ModuleConfiguration
 * @package Codilar\Zipcode\Model\Config
 */
class ModuleConfiguration
{
    const VENDOR_MODULE_STATUS = 'zipcode/general/enable';
    private $config;

    /**
     * ModuleConfiguration constructor.
     * @param ScopeConfigInterface $config
     */
    public function __construct(
        ScopeConfigInterface $config
    )
    {
        $this->config = $config;
    }

    public function getModuleStatus(){
        return !!$this->config->getValue(self::VENDOR_MODULE_STATUS);
    }
}
