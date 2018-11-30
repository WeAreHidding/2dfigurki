<?php

class Figures_Cms_Adminhtml_HeaderController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function editAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function saveEditAction()
    {
        $data = $this->getRequest()->getParams();
        $this->_getHeaderModel()->saveHeaderItem($data);

        $this->_redirectUrl($this->getUrl('adminhtml/header/index'));
    }

    public function saveNewAction()
    {
        $data = $this->getRequest()->getParams();
        $this->_getHeaderModel()->addHeaderItem($data);

        $this->_redirectUrl($this->getUrl('adminhtml/header/index'));
    }

    public function saveStoreNameAction()
    {
        $storeName = $this->getRequest()->getParam('store_name');

        Mage::getModel('core/config')->saveConfig('general/store_information/name', $storeName);
        $this->_redirectUrl($this->getUrl('adminhtml/header/index'));
    }

    /**
     * @return Figures_Cms_Model_Header
     */
    protected function _getHeaderModel()
    {
        return Mage::getModel('figures_cms/header');
    }
}