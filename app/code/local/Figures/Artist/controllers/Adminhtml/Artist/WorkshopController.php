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

    public function createProductAction()
    {
        $params = $this->getRequest()->getParams();
        $dataArray = json_decode($params['productData'], true);

        $formToCreate = $genreToCreate = $gIToCreate = true;
        if (!$formCategory = $dataArray['form']) {
            $formCategory = $dataArray['form_old'];
            $formToCreate = false;
        }
        if (!$genreCategory = $dataArray['genre']) {
            $genreCategory = $dataArray['genre_old'];
            $genreToCreate = false;
        }
        if (!$gICategory = $dataArray['genre_item']) {
            $gICategory = $dataArray['genre_item_old'];
            $gIToCreate = false;
        }
        if ($formToCreate) {
            $this->_getProductCreatorModel()->createCategory(['name' => $formCategory, 'category_custom_type' => 'FORM']);
        }
        if ($genreToCreate) {
            $this->_getProductCreatorModel()->createCategory(['name' => $genreCategory, 'category_custom_type' => 'GENRE']);
        }
        if ($gIToCreate) {
            $this->_getProductCreatorModel()->createCategory(['name' => $gICategory, 'category_custom_type' => 'GENRE_ITEM']);
        }

        $productData = [
            'name' => $dataArray['title'],
            'sku'  => $dataArray['sku'],
            'price' => $dataArray['price'],
            'parent_cat' => $gICategory
        ];

        $this->_getProductCreatorModel()->createProduct($productData);

    }

    /**
     * @return Figures_Artist_Model_Artist
     */
    protected function _getArtistModel()
    {
        return Mage::getModel('figures_artist/artist');
    }

    /**
     * @return Figures_Artist_Model_ProductCreator
     */
    protected function _getProductCreatorModel()
    {
        return Mage::getModel('figures_artist/productCreator');
    }

    /**
     * @return Varien_Db_Adapter_Pdo_Mysql
     */
    protected function _getConnection()
    {
        return Mage::getModel('core/resource')->getConnection('core_write');
    }
}