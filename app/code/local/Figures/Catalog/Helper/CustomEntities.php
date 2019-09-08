<?php
class Figures_Catalog_Helper_CustomEntities extends Mage_Core_Helper_Abstract
{
    /**
     * @param array $filters
     * @return mixed
     */
    public function getFormCategories($filters = [])
    {
        return $this->getCustomEntitiesChildCategories(CustomEntities::FORM_ID, $filters);
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function getGenreCategories($filters = [])
    {
        return $this->getCustomEntitiesChildCategories(CustomEntities::GENRE_ID, $filters);
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function getFandomCategories($filters = [])
    {
        return $this->getCustomEntitiesChildCategories(CustomEntities::FANDOM_ID, $filters);
    }

    /**
     * Used for category filters (bad title)
     *
     * @param $category
     * @return mixed
     */
    public function getCategoryType($category)
    {
        return CustomEntities::getUrlById($category->getData('parent_id'));
    }

    /**
     * @param $customEntityId
     * @param $filters
     * @param $asArray
     *
     * @return mixed
     */
    public function getCustomEntitiesChildCategories($customEntityId, $filters = [], $asArray = false)
    {
        $categories = Mage::getModel('catalog/category')->getCollection()
            ->addAttributeToSelect('*')
            ->addAttributeTofilter('parent_id', $customEntityId);

        if ($filters) {
            foreach ($filters as $attributeName => $filter) {
                $categories->addAttributeToFilter($attributeName, $filter);
            }
        }

        if ($asArray) {
            $array = [];
            foreach ($categories as $category) {
                $array[] = $category->getId();
            }
            $categories = $array;
        }

        return $categories;
    }

    public function getCategoriesForProduct($customEntityId, $product)
    {
        $productCategoriesIds = $categoriesForProduct = [];
        $availableCategories = $this->getCustomEntitiesChildCategories($customEntityId, []);
        foreach ($product->getCategoryCollection() as $category) {
            $productCategoriesIds[] = $category->getId();
        }
        foreach ($availableCategories as $availableCategory) {
            if (in_array($availableCategory->getId(), $productCategoriesIds)) {
                $categoriesForProduct[$availableCategory->getId()] = $availableCategory->getName();
            }
        }

        return $categoriesForProduct;
    }
}