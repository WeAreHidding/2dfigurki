<?php
/**
 * Essive
 */
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;

$installer->startSetup();
$installer->run(
    "
ALTER TABLE `artist_product`
  ADD COLUMN main_tag VARCHAR(255) DEFAULT NULL 
"
);
$installer->endSetup();
