<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$eav =  Mage::getResourceModel('eav/entity_attribute');
$installer->startSetup();

if (!($eav->getIdByCode('catalog_category', 'sku_prefix'))) {
    $attribute_data = array(
        'group'             => 'General',
        'type'              => 'varchar',
        'backend'           => '',
        'frontend'          => '',
        'label'             => 'Sku prefix',
        'input'             => 'text',
        'class'             => '',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible'           => true,
        'required'          => false,
        'user_defined'      => false,
        'default'           => '',
        'searchable'        => false,
        'filterable'        => false,
        'comparable'        => false,
        'visible_on_front'  => true,
        'unique'            => false,
        'note'              => 'Affects only Form categories, Default - first 3 symbols of current form category'
    );

    $setup->addAttribute('catalog_category', 'sku_prefix', $attribute_data);
}

$installer->endSetup();
