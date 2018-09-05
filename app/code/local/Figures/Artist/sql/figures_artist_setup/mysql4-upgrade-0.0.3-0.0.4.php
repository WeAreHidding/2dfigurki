<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$setup = new Mage_Eav_Model_Entity_Setup('core_setup');
$eav =  Mage::getResourceModel('eav/entity_attribute');
$installer->startSetup();

if (!($eav->getIdByCode('catalog_category', 'category_custom_type'))) {
    $attribute_data = array(
        'group'             => 'General',
        'type'              => 'varchar',
        'backend'           => '',
        'frontend'          => '',
        'label'             => 'Category Type (Custom)',
        'input'             => 'select',
        'class'             => '',
        'source'            => 'figures_artist/source_categoryType',
        'global'            => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'visible'           => true,
        'required'          => false,
        'user_defined'      => false,
        'default'           => 0,
        'searchable'        => false,
        'filterable'        => false,
        'comparable'        => false,
        'visible_on_front'  => true,
        'unique'            => false,
    );

    $setup->addAttribute('catalog_category', 'category_custom_type', $attribute_data);
}

$installer->endSetup();
