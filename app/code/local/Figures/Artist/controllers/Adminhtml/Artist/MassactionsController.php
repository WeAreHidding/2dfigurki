<?php

class Figures_Artist_Adminhtml_Artist_MassactionsController extends Mage_Adminhtml_Controller_Action
{
    public function massDeleteAction()
    {
        $this->_getArtistModel()->deleteDesigns($this->getRequest()->getParam('id'));
        $this->_redirectReferer($this->getUrl('adminhtml/artist_workshop/index'));
    }

    /**
     * @return Figures_Artist_Model_Artist
     */
    protected function _getArtistModel()
    {
        return Mage::getModel('figures_artist/artist');
    }
}