<?php
/**
 * Essive
 */

class Figures_Artist_Block_Adminhtml_Workshop_Forms_General extends Figures_Artist_Block_Adminhtml_Workshop_Forms_Abstract
{
    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->setHtmlId('analog_parent_form');
        $this->setTemplate('artist/general_form.phtml');

        $this->_rowId = $this->getRequest()->getParam('id');
        $this->_initRowData();
    }
}