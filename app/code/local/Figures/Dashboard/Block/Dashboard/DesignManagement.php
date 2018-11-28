<?php

class Figures_Dashboard_Block_Dashboard_DesignManagement extends Figures_Dashboard_Block_Dashboard
{
    public function getDesignData()
    {
        return $this->_getArtistModel()->getDesignsByCustomer(Mage::getSingleton('customer/session')->getCustomer()->getId());
    }

    public function getStatusHtml($status)
    {
        switch ($status) {
            case 'Products created':
                return '<li class="text-accept">Product(s) Created</li>';
                break;
            case 'Declined':
                return  '<li class="text-not-accept">Rejected</li>';
                break;
            default:
                return '<li>' . $status . '</li>';
        }
    }

    /**
     * @return Figures_Artist_Model_Artist
     */
    protected function _getArtistModel()
    {
        return Mage::getModel('figures_artist/artist');
    }
}