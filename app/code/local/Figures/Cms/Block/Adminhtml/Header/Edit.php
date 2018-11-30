<?php
/**
 * Essive
 */

class Figures_Cms_Block_Adminhtml_Header_Edit extends Mage_Adminhtml_Block_Widget
{
    protected $_rowId = [];
    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->setHtmlId('edit_header_item');
        $this->setTemplate('cms/edit_header.phtml');

        $this->_rowId = $this->getRequest()->getParam('id');
    }

    public function getRowData()
    {
        return Mage::getModel('figures_cms/header')->getItemById($this->_rowId);
    }
}