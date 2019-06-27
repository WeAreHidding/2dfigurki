<?php
/**
 * Essive
 */
/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */
$installer = $this;

$installer->startSetup();
$connection = $installer->getConnection();

$categoryIdsNamesPairs = $connection->fetchPairs(
    $connection->select()
        ->from('catalog_category_entity_varchar', ['entity_id', 'value'])
        ->where('value IN (?)', [CustomEntities::FORM_NAME, CustomEntities::GENRE_NAME, CustomEntities::FANDOM_NAME])
        ->where('store_id = 0')
);

foreach ($categoryIdsNamesPairs as $oldCategoryId => $categoryName) {
    $newCategoryId = CustomEntities::getCategoryIdByName($categoryName);
    $connection->update(
        'catalog_category_entity',
        ['entity_id' => $newCategoryId, 'path' => '1/2/' . $newCategoryId],
        'entity_id=' . $oldCategoryId
    );
    $connection->update(
        'catalog_category_entity_varchar',
        ['entity_id' => $newCategoryId],
        'entity_id=' . $oldCategoryId
    );
    $connection->update(
        'catalog_category_entity_datetime',
        ['entity_id' => $newCategoryId],
        'entity_id=' . $oldCategoryId
    );
    $connection->update(
        'catalog_category_entity_decimal',
        ['entity_id' => $newCategoryId],
        'entity_id=' . $oldCategoryId
    );
    $connection->update(
        'catalog_category_entity_int',
        ['entity_id' => $newCategoryId],
        'entity_id=' . $oldCategoryId
    );
    $connection->update(
        'catalog_category_entity_text',
        ['entity_id' => $newCategoryId],
        'entity_id=' . $oldCategoryId
    );
    $connection->update(
        'catalog_category_product',
        ['category_id' => $newCategoryId],
        'category_id=' . $oldCategoryId
    );
}

$installer->endSetup();