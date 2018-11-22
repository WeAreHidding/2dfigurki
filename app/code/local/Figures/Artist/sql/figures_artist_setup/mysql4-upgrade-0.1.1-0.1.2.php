<?php
$installer = new Mage_Eav_Model_Entity_Setup('core_setup');
$installer->startSetup();
$installer->addAttribute('catalog_product', 'tags', array(
    'group'           => 'General',
    'label'           => 'Tags',
    'input'           => 'text',
    'type'            => 'varchar',
    'required'        => 0,
    'visible_on_front'=> 1,
    'filterable'      => 0,
    'searchable'      => 0,
    'comparable'      => 0,
    'user_defined'    => 1,
    'is_configurable' => 0,
    'global'          => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'note'            => 'Separated by comma (,)',
));

$installer->addAttribute('catalog_product', 'main_tag', array(
    'group'           => 'General',
    'label'           => 'Main tag',
    'input'           => 'text',
    'type'            => 'varchar',
    'required'        => 0,
    'visible_on_front'=> 1,
    'filterable'      => 0,
    'searchable'      => 0,
    'comparable'      => 0,
    'user_defined'    => 1,
    'is_configurable' => 0,
    'global'          => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'note'            => '',
));

$installer->addAttribute('catalog_product', 'artist_id', array(
    'group'           => 'General',
    'label'           => 'Artist Id',
    'input'           => 'text',
    'type'            => 'varchar',
    'required'        => 0,
    'visible_on_front'=> 1,
    'filterable'      => 0,
    'searchable'      => 0,
    'comparable'      => 0,
    'user_defined'    => 1,
    'is_configurable' => 0,
    'global'          => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
    'note'            => 'Id of creator',
));
$installer->endSetup();
?>