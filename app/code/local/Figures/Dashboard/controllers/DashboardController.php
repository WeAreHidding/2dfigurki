<?php

class Figures_Dashboard_DashboardController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
//        die();
        $this->loadLayout();
        $this->renderLayout();
    }
}