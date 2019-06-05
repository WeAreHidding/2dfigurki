<?php

class Figures_Catalog_SearchController extends Mage_Core_Controller_Front_Action
{
    public function searchAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }
}