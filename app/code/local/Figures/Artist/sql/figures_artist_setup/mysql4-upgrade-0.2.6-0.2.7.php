<?php
/**
 * Essive
 */
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;

$installer->startSetup();

$installer->removeAttribute('catalog_category', 'category_custom_type');

/** @var Figures_Artist_Model_ProductCreator $productCreator */
$productCreator = Mage::getSingleton('figures_artist/productCreator');

$installer->getConnection()->delete('catalog_category_entity', ['entity_id > 2']);
$installer->getConnection()->delete('catalog_category_entity_varchar', ['entity_id > 2']);
$installer->getConnection()->delete('catalog_category_entity_datetime', ['entity_id > 2']);
$installer->getConnection()->delete('catalog_category_entity_decimal', ['entity_id > 2']);
$installer->getConnection()->delete('catalog_category_entity_int', ['entity_id > 2']);
$installer->getConnection()->delete('catalog_category_entity_text', ['entity_id > 2']);
$installer->getConnection()->delete('catalog_category_product', ['category_id > 2']);

foreach (CustomEntities::getAttributesBunch() as $categoryId => $data) {
    $productCreator->createCategory($data);
}

$installer->endSetup();