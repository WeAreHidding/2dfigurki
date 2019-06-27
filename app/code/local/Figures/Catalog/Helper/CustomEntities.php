<?php
class Figures_Catalog_Helper_CustomEntities extends Mage_Core_Helper_Abstract
{
    /**
     * @param array $filters
     * @return mixed
     */
    public function getFormCategories($filters = [])
    {
        return $this->_getCategory(CustomEntities::FORM_ID, $filters);
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function getGenreCategories($filters = [])
    {
        return $this->_getCategory(CustomEntities::GENRE_ID, $filters);
    }

    /**
     * @param array $filters
     * @return mixed
     */
    public function getFandomCategories($filters = [])
    {
        return $this->_getCategory(CustomEntities::FANDOM_ID, $filters);
    }

    /**
     * @param $categoryId
     * @param $filters
     *
     * @return mixed
     */
    protected function _getCategory($categoryId, $filters)
    {
        $categories = Mage::getModel('catalog/category')->getCollection()
            ->addAttributeToSelect('*')
            ->addAttributeTofilter('parent_id', $categoryId);

        if ($filters) {
            foreach ($filters as $attributeName => $filter) {
                $categories->addAttributeToFilter($attributeName, $filter);
            }
        }
        return $categories;
    }
}