<?php
/**
 * Essive
 */

class Figures_Cms_Block_Adminhtml_Header_StoreName extends Mage_Adminhtml_Block_Widget
{
    public function __construct()
    {
        parent::__construct();
        $this->setHtmlId('store_name');
        $this->setTemplate('cms/store_name.phtml');
    }

    public function getStoreName()
    {
        return Mage::getStoreConfig('general/store_information/name');
    }
}