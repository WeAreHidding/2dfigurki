<?php
/**
 * Essive
 */
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;

$installer->startSetup();
$installer->run(
    "
CREATE TABLE artist_sales
(
  id                   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  artist_id            INT                 NOT NULL,
  product_id           INT UNSIGNED DEFAULT '0'         NOT NULL,
  work_id              INT UNSIGNED DEFAULT '0'         NOT NULL,
  order_item_id        INT UNSIGNED DEFAULT '0'         NOT NULL,
  qty_sold             INT(12) DEFAULT '0' NULL,
  price                DECIMAL(12, 2)      NULL,
  discount             INT(12) DEFAULT '0' NULL,
  artist_comission     INT(12) DEFAULT '0' NULL,
  order_status         VARCHAR (255) DEFAULT 'pending' NULL,
  artist_comission_status VARCHAR (255) DEFAULT 'pending' NULL,
  created_at           TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT sale_product_id_key FOREIGN KEY (product_id)
  REFERENCES catalog_product_entity(entity_id)
    ON DELETE CASCADE,
  CONSTRAINT sale_work_id_key FOREIGN KEY (work_id)
  REFERENCES artist_work(id)
    ON DELETE CASCADE
)
  ENGINE = InnoDB;


"
);
$installer->endSetup();
