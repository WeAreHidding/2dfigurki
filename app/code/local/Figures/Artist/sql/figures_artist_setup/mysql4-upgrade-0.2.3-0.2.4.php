<?php

/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->getConnection()
    ->addColumn('artist_sales', 'artist_comission_net', 'DECIMAL(10, 2)');

$installer->endSetup();