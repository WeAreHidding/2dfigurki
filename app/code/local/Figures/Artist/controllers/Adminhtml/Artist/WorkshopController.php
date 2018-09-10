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
        $this->renderLayout();
    }

    public function saveGeneralAction()
    {
        $params = $this->getRequest()->getParams();
        $connection = $this->_getConnection();

        $connection->update('artist_work',
            ['description' => $params['description'], 'tags' => $params['tags']], 'id=' . $params['id']);
    }

    public function saveStatusAction()
    {
        $params = $this->getRequest()->getParams();
        $connection = $this->_getConnection();

        $connection->update('artist_work',
            ['status' => $params['status']], 'id=' . $params['id']);
    }

    public function editProductsAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * @return Figures_Artist_Model_Artist
     */
    protected function _getArtistModel()
    {
        return Mage::getModel('figures_artist/artist');
    }

    /**
     * @return Varien_Db_Adapter_Pdo_Mysql
     */
    protected function _getConnection()
    {
        return Mage::getModel('core/resource')->getConnection('core_write');
    }
}