<?php

class Figures_Dashboard_Block_Dashboard extends Mage_Core_Block_Template
{
    public function isLoggedIn()
    {
        return (bool)Mage::getSingleton('customer/session')->getCustomer()->getId();
    }
}