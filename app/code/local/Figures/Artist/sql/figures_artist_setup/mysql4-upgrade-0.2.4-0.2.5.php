<?php
/**
 * Essive
 */
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;

$installer->startSetup();
$installer->run(
    "
CREATE TABLE artist_comission_log
(
  id                   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  artist_id            INT                 NOT NULL,
  value                VARCHAR (255) DEFAULT  0,
  description          VARCHAR (255) DEFAULT  NULL,
  created_at           TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
  ENGINE = InnoDB;


"
);

$installer->run(
    "
CREATE TABLE artist_money
(
  id                   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  artist_id            INT                 NOT NULL,
  value                DECIMAL (10,2) DEFAULT  0
)
  ENGINE = InnoDB;


"
);

$installer->run(
    "
CREATE TABLE artist_money_log
(
  id                   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  artist_id            INT                 NOT NULL,
  value                VARCHAR (255) DEFAULT  0,
  description          VARCHAR (255) DEFAULT  NULL,
  created_at           TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)
  ENGINE = InnoDB;


"
);
$installer->endSetup();
