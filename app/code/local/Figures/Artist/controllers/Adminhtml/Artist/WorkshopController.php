<?php

class Figures_Artist_Adminhtml_Artist_WorkshopController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function gridAction()
    {

        $this->loadLayout();
        $this->getResponse()->setBody(
            $this->getLayout()->createBlock('figures_artist/adminhtml_workshop_grid')->toHtml()
        );
    }
}