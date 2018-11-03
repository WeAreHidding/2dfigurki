<?php

class Figures_Dashboard_Block_Dashboard_Design extends Figures_Dashboard_Block_Dashboard
{
    protected function _getCategoryByFilter($customType)
    {
        $categoryData = [];
        $categories = Mage::getModel('catalog/category')
            ->getCollection()
            ->addAttributeToSelect('*')
            ->addFieldToFilter('category_custom_type', $customType)
            ->addIsActiveFilter();

        foreach ($categories as $category) {
            $categoryData[] = [
                'name' => $category->getName(),
                'id'   => $category->getId()
            ];
        }

        return $categoryData;
    }

    public function getFormCategories()
    {
        return $this->_getCategoryByFilter('FORM');
    }
}