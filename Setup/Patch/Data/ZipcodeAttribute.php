<?php
namespace Codilar\Zipcode\Setup\Patch\Data;

use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
/**
 * Class Vendor
 * @package Dhruvi\Vendor\Setup\Patch\Data
 */
class ZipcodeAttribute implements DataPatchInterface {

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;


    /**
     * @var EavSetupFactory
     */
    private $eavSetupFactory;


    /**
     * Vendor constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param EavSetupFactory $eavSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        EavSetupFactory $eavSetupFactory
    )
    {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->eavSetupFactory = $eavSetupFactory;
    }


    /**
     * Run code inside patch
     * If code fails, patch must be reverted, in case when we are speaking about schema - then under revert
     * means run PatchInterface::revert()
     *
     * If we speak about data, under revert means: $transaction->rollback()
     *
     * @return $this
     */
    public function apply() {

        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);

        $eavSetup->addAttribute(\Magento\Catalog\Model\Product::ENTITY, 'region_zipcodes', [
            'group' => 'General',
            'type' => 'text',
            'backend' => '',
            'frontend' => '',
            'sort_order' => 210,
            'label' => 'Product Availability',
            'input' => 'multiselect',
            'class' => '',
            'source' => 'Codilar\Zipcode\Model\Config\Source\Regions',
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
            'visible' => true,
            'required' => false,
            'user_defined' => false,
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => true,
            'apply_to' => ''
        ]);
    }
    /** Add Revert Part => that will undo your patch when you run bin/magento mo:un Codilar_Zipocde => So you dont have to delete from DB  **/
    /**
     * {@inheritdoc}
     */
    public function revert()
    {
        $this->moduleDataSetup->getConnection()->startSetup();
        $eavSetup = $this->eavSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $eavSetup->removeAttribute(
            Product::ENTITY,
            self::region_zipcodes
        );
        $this->moduleDataSetup->getConnection()->endSetup();
    }

    public static function getDependencies() {
        return [];
    }
    public function getAliases() {
        return [];
    }
}
