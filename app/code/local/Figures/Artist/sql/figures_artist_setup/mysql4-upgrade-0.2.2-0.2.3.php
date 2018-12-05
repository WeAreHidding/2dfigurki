<?php
/**
 * Essive
 */
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;

$installer->startSetup();
$installer->run(
    "
CREATE TABLE artist_genre
(
  id                   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name                 VARCHAR(255)
)
  ENGINE = InnoDB;


"
);

$installer->run(
    "
CREATE TABLE artist_fandom
(
  id                   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name                 VARCHAR(255)
)
  ENGINE = InnoDB;


"
);

$installer->addAttribute('catalog_product', 'work_id', array(
    'group'           => 'General',
    'label'           => 'Design Id',
    'input'           => 'text',
    'type'            => 'varchar',
    'required'        => 0,
    'visible_on_front'=> 1,
    'filterable'      => 0,
    'searchable'      => 0,
    'comparable'      => 0,
    'user_defined'    => 1,
    'is_configurable' => 0,
    'global'          => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL
));

$installer->addAttribute('catalog_product', 'genre_id', array(
    'group'           => 'General',
    'label'           => 'Genre Id',
    'input'           => 'text',
    'type'            => 'varchar',
    'required'        => 0,
    'visible_on_front'=> 1,
    'filterable'      => 0,
    'searchable'      => 0,
    'comparable'      => 0,
    'user_defined'    => 1,
    'is_configurable' => 0,
    'global'          => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL
));

$installer->addAttribute('catalog_product', 'fandom_id', array(
    'group'           => 'General',
    'label'           => 'Fandom Id',
    'input'           => 'text',
    'type'            => 'varchar',
    'required'        => 0,
    'visible_on_front'=> 1,
    'filterable'      => 0,
    'searchable'      => 0,
    'comparable'      => 0,
    'user_defined'    => 1,
    'is_configurable' => 0,
    'global'          => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL
));

$installer->endSetup();
