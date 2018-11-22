<?php

class Figures_Dashboard_Block_Dashboard_DesignManagement extends Figures_Dashboard_Block_Dashboard
{
    public function getDesignData()
    {
        return $this->_getArtistModel()->getDesignsByCustomer(Mage::getSingleton('customer/session')->getCustomer()->getId());
    }

    /**
     * @return Figures_Artist_Model_Artist
     */
    protected function _getArtistModel()
    {
        return Mage::getModel('figures_artist/artist');
    }
}