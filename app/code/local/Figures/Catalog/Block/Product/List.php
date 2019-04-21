<?php

class Figures_Catalog_Block_Product_List extends Mage_Catalog_Block_Product_Abstract
{
    protected $_definedFilters = [
        'genre_id',
        'artist_id'
    ];

    public function getAvailableFilters()
    {
        $category = Mage::getModel('catalog/category')->load($this->getCategoryId());
        $filters = [];
        $productCollection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('*')
            ->addCategoryFilter($category)
            ->load();

        foreach ($productCollection as $product) {
            foreach ($this->_definedFilters as $definedFilter) {
                $filters[$definedFilter][] = $product->getData($definedFilter);
            }
        }

        if (!$filters) {
            return false;
        }

        $filters = $this->_getCategoryFiltersHelper()->formatFiltersForFrontend($filters);

        return $filters;
    }

    /**
     * @return Figures_Catalog_Helper_CategoryFilters
     */
    protected function _getCategoryFiltersHelper()
    {
        return Mage::helper('figures_catalog/categoryFilters');
    }
}
