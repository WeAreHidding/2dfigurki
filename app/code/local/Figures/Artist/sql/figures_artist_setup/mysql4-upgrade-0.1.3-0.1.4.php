<?php
/**
 * Essive
 */
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;

$installer->startSetup();
$installer->run(
    "
DROP TABLE artist_product;
CREATE TABLE artist_product
(
  id                   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  artist_id            INT                 NOT NULL,
  product_id           INT UNSIGNED DEFAULT '0'         NOT NULL,
  work_id              INT UNSIGNED DEFAULT '0'         NOT NULL,
  main_tag             VARCHAR(255)        NULL,
  parent_form_category INT(12) DEFAULT '0' NULL,
  total_sold           INT(12)             NULL,
  total_income         DECIMAL(12, 2)      NULL,
  CONSTRAINT design_product_id_key FOREIGN KEY (product_id)
  REFERENCES catalog_product_entity(entity_id)
    ON DELETE CASCADE,
  CONSTRAINT design_work_id_key FOREIGN KEY (work_id)
  REFERENCES artist_work(id)
    ON DELETE CASCADE
)
  ENGINE = InnoDB;


"
);
$installer->endSetup();
