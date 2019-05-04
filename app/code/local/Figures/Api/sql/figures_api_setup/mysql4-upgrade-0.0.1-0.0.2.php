<?php
/**
 * Essive
 */
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;
$installer->startSetup();
$connection = $installer->getConnection();
$table = 'core_config_data';

$connection->insert($table, ['path' => 'etsy_consumer_key', 'value' => '']);
$connection->insert($table, ['path' => 'etsy_consumer_secret', 'value' => '']);

$connection->insert($table, ['path' => 'etsy_last_oauth_token_secret', 'value' => '']);

$connection->insert($table, ['path' => 'etsy_oauth_token', 'value' => '']);
$connection->insert($table, ['path' => 'etsy_oauth_token_secret', 'value' => '']);

$installer->endSetup();
