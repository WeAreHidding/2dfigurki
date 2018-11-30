<?php

class Figures_Cms_Block_Header extends Mage_Page_Block_Html_Header
{
    public function getStoreName()
    {
        return Mage::getStoreConfig('general/store_information/name');
    }

    public function getHeaderItems()
    {
        return $this->_getHeaderModel()->getFrontendItems();
    }

    /**
     * @return Figures_Cms_Model_Header
     */
    protected function _getHeaderModel()
    {
        return Mage::getModel('figures_cms/header');
    }
}