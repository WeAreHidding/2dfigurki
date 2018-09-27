<?php
/**
 * Essive
 */
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;

$installer->startSetup();
$installer->run(
    "
ALTER TABLE `artist_work`
  ADD COLUMN proposed_form_category VARCHAR(255),
  ADD COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
  AFTER proposed_genre_category;
"
);
$installer->endSetup();
