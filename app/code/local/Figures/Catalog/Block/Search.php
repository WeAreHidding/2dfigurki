<?php

class Figures_Catalog_Block_Search extends Mage_Core_Block_Template
{
    public function getSearchResult()
    {
        var_dump($this->_getSearchModel()->getSearchResults('shi')); die();
    }

    /**
     * @return false|Figures_Catalog_Model_Search
     */
    protected function _getSearchModel()
    {
        return Mage::getModel('figures_catalog/search');
    }
}