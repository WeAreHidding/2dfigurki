<?php
/**
 * Essive
 */
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;

$installer->startSetup();

$this->addAttribute('catalog_product', 'is_mature', array(
    'group'         => 'General',
    'input'         => 'select',
    'type'          => 'text',
    'label'         => 'Is mature',
    'backend'       => '',
    'visible'       => true,
    'required'      => false,
    'visible_on_front' => true,
    'searchable'      => 0,
    'comparable'      => 0,
    'user_defined'    => 1,
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'source' => 'eav/entity_attribute_source_boolean',
));

$installer->endSetup();