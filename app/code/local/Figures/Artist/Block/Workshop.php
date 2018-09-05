<?php

class Figures_Artist_Block_Workshop extends Mage_Core_Block_Template
{
    public function getArtistNickname()
    {
//        var_dump(123); die();
        $session = Mage::getSingleton('customer/session')->getCustomer();
        $customer = Mage::getModel('customer/customer')->load($session->getId());

        return $customer->getData('artist_nickname') ?: '';
    }
}