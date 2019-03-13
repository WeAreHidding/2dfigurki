<?php
/**
 * Essive
 */

class Figures_Api_Block_Adminhtml_Etsy_Create extends Figures_Api_Block_Adminhtml_Etsy_Form
{
    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->setHtmlId('etsy_create');
        $this->setTemplate('a_api/etsy_create.phtml');
    }
}