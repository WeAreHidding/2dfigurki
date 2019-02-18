<?php
/**
 * Essive
 */
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;
$installer->startSetup();

$sql = "
CREATE TABLE IF NOT EXISTS `etsy_favorite_functions`
(
    `id` INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `method_name` VARCHAR(255)
);
";
$installer->run($sql);
$installer->endSetup();
