<?php

class Figures_Catalog_Block_Product_List extends Mage_Catalog_Block_Product_Abstract
{
    protected $_definedFilters = [
        'artist',
        'genre',
        'fandom'
    ];

    public function getAvailableFilters()
    {
        $category = Mage::getModel('catalog/category')->load($this->getCategoryId());
        $productCollection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('*')
            ->addCategoryFilter($category)
            ->load();

       return $this->_getCategoryFiltersHelper()->getAvailableFilters($productCollection, $category);
    }

    /**
     * @return Figures_Catalog_Helper_CategoryFilters
     */
    protected function _getCategoryFiltersHelper()
    {
        return Mage::helper('figures_catalog/categoryFilters');
    }
}
