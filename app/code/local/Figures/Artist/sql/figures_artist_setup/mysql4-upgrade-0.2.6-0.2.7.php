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

foreach (CustomEntities::getAttributesBunch() as $categoryId => $data) {
    $installer->getConnection()->delete('catalog_category_entity', ['entity_id = ?' => $categoryId]);
    $productCreator->createCategory($data);
}

$installer->endSetup();