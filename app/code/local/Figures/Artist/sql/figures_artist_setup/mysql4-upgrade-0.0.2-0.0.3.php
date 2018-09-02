<?php
/**
 * Essive
 */
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;

$installer->startSetup();
    $installer->run(
        "
ALTER TABLE `{$installer->getTable('figures_artist/artist')}`
  ADD COLUMN tags VARCHAR(255),
  ADD COLUMN status VARCHAR(255) DEFAULT 'Pending',
  ADD COLUMN created_products_qty INT DEFAULT 0,
  ADD COLUMN proposed_type_category VARCHAR(255),
  ADD COLUMN proposed_genre_category VARCHAR(255)
  AFTER description;
"
    );
$installer->endSetup();
