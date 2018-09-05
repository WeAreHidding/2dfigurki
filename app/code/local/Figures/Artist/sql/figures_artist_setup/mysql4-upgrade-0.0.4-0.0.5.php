<?php
/**
 * Essive
 */
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;
$installer->startSetup();

$sql = "
CREATE TABLE IF NOT EXISTS `artist_product`
(
    `id` INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `artist_id` INT NOT NULL,
    `product_id` INT NOT NULL
)
";
$installer->run($sql);
$installer->endSetup();
