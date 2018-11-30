<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->getConnection()
    ->addColumn('custom_cms_header', 'position', 'INT(10)');
$installer->getConnection()
    ->addColumn('custom_cms_header', 'is_enabled', 'INT(2)');

$installer->endSetup();