<?php
/**
 * Essive
 */
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;
$installer->startSetup();

$sql = "
CREATE TABLE IF NOT EXISTS `custom_cms_header`
(
    `id` INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(255),
    `link`        VARCHAR(255),
    `image_path`  VARCHAR(255),
    `level`       INT(10),
    `parent_id`   INT(10)
);
";
$installer->run($sql);
$installer->endSetup();
