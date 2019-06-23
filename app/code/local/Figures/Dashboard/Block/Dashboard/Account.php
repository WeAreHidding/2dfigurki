<?php

class Figures_Dashboard_Block_Dashboard_Account extends Figures_Dashboard_Block_Dashboard
{
    public function getAccountData(){
        $customer = Mage::getSingleton('customer/session')->getCustomer();
        return $customer->getData();
    }



}