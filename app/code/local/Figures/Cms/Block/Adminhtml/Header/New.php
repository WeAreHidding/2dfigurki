<?php
/**
 * Essive
 */

class Figures_Cms_Block_Adminhtml_Header_New extends Mage_Adminhtml_Block_Widget
{
    public function __construct()
    {
        parent::__construct();
        $this->setHtmlId('new_header_item');
        $this->setTemplate('cms/new_header_item.phtml');
    }
}