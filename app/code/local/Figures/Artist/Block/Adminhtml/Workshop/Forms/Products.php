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

    /**
     * @return array|bool
     */
    public function getProposedFormCategories()
    {
        $formCategories = $this->getFormCategories();
        $proposedCategories = $this->getEditableData()['proposed_form_category'];
        $productData = $this->_getArtistModel()->getProductDataByWorkId($this->_rowId);
        $createdFormIds = array_column($productData, 'parent_form_category');
        if (!$formCategories || !$proposedCategories) {
            return false;
        }
        $proposedCategories = explode(',', $proposedCategories);
        $result = [];
        foreach ($formCategories as $formCategory) {
            if (in_array($formCategory['id'], $createdFormIds)) {
                continue;
            }
            $isUsed = in_array($formCategory['id'], $proposedCategories) ? 1 : 0;
            if (!$isUsed) {
                continue;
            }
            $result[] = $formCategory;
        }

        return $result;
    }

    public function getGenreCategories($parentId = false)
    {
        return $this->getCategoryByFilter('GENRE', $parentId);
    }

    public function getGenreItemCategories()
    {
        return $this->getCategoryByFilter('GENRE_ITEM');
    }
}