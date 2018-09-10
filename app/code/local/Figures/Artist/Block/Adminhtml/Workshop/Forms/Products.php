<?php
/**
 * Essive
 */

class Figures_Artist_Block_Adminhtml_Workshop_Forms_Products extends Figures_Artist_Block_Adminhtml_Workshop_Forms_Abstract
{
    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->setHtmlId('analog_parent_products_form');
        $this->setTemplate('artist/products_form.phtml');

        $this->_rowId = $this->getRequest()->getParam('id');
        $this->_initRowData();
    }

    public function getFormCategories()
    {
        return $this->getCategoryByFilter('FORM');
    }

    public function getGenreCategories()
    {
        return $this->getCategoryByFilter('GENRE');
    }

    public function getGenreItemCategories()
    {
        return $this->getCategoryByFilter('GENRE_ITEM');
    }

    public function getCategoryByFilter($customType)
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
}