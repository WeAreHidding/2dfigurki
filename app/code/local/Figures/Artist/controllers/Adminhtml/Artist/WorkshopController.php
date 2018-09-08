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

    public function editGeneralAction()
    {
        $this->loadLayout();
//        $this->getLayout()->createBlock('workshop_general')->toHtml();
//        $this->_addContent($this->getLayout()->createBlock('figures_artist/adminhtml_workshop_edit'))
//            ->_addLeft($this->getLayout()->createBlock('figures_artist/adminhtml_workshop_edit_tab_generalInfo'));
        $this->renderLayout();
    }

    public function saveGeneralAction()
    {
        var_dump(12512); die();
    }
}