<?php

class Figures_Cms_Block_Header extends Mage_Page_Block_Html_Header
{
    protected $_session = null;

    public function getStoreName()
    {
        return Mage::getStoreConfig('general/store_information/name');
    }

    public function getHeaderItems()
    {
        return $this->_getHeaderModel()->getFrontendItems();
    }

    /**
     * Retrieve customer session model object
     *
     * @return Mage_Customer_Model_Session
     */
    public function getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    /**
     * @return Figures_Cms_Model_Header
     */
    protected function _getHeaderModel()
    {
        return Mage::getModel('figures_cms/header');
    }
}