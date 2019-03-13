<?php
/**
 * Essive
 */
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;
$installer->startSetup();
$connection = $installer->getConnection();
$table = 'etsy_favorite_functions';

$connection->insert($table, ['method_name' => 'findAllReceiptListings']);
$connection->insert($table, ['method_name' => 'findAllShopReceiptsByStatus']);

$installer->endSetup();