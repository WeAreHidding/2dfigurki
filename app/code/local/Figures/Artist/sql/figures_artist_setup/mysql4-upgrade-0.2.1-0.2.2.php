<?php
/**
 * Essive
 */
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;

$installer->startSetup();
$installer->run(
    "
CREATE TABLE artist_comission
(
  id                   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  artist_id            INT                 NOT NULL,
  value                INT(10) DEFAULT  0
)
  ENGINE = InnoDB;


"
);
$installer->endSetup();
