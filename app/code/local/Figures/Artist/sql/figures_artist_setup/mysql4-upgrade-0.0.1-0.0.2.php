<?php
/**
 * Essive
 */
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;
$installer->startSetup();

$sql = "
CREATE TABLE IF NOT EXISTS `{$installer->getTable('figures_artist/artist')}`
(
    `id` INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `customer_id` INT NOT NULL,
    `artist_name` VARCHAR(255),
    `char_name`   VARCHAR(255),
    `description` VARCHAR(255),
    `image_path`  VARCHAR(255)
);
";
$installer->run($sql);
$installer->endSetup();
