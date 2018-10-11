<?php

class Figures_Artist_Adminhtml_Artist_WorkshopController extends Mage_Adminhtml_Controller_Action
{
    protected $_generalParams = [
        'form_key',
        'description',
        'tags'
    ];

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

    /**
     * @throws Zend_Db_Adapter_Exception
     */
    public function saveGeneralAction()
    {
        $params = $this->getRequest()->getParams();

        $formCategories = [];
        foreach ($params as $key => $param) {
            if (in_array($key, $this->_generalParams)) {
                continue;
            }
            $formCategories[] = $key;
        }
        if ($formCategories) {
            $formCategories = implode(',', $formCategories);
        }

        $connection = $this->_getConnection();

        $connection->update('artist_work',
            ['description' => $params['description'], 'tags' => $params['tags'], 'proposed_form_category' => $formCategories],
            'id=' . $params['id']);

        $this->_redirectReferer($this->getUrl('adminhtml/artist_workshop/editGeneral') . 'id/' . $params['id']);
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
        $dataArray = $this->getRequest()->getParams();
        $formToCreate = $genreToCreate = $gIToCreate = $fCatId = $gCatId = true;

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
            $fCatId = $this->_getProductCreatorModel()->createCategory(['name' => $formCategory, 'category_custom_type' => 'FORM']);
        }
        if ($genreToCreate) {
            $gCatId = $this->_getProductCreatorModel()->createCategory(['name' => $genreCategory, 'category_custom_type' => 'GENRE', 'parent_id' => $fCatId ?: $dataArray['genre_item_old']]);
        }
        if ($gIToCreate) {
            $this->_getProductCreatorModel()->createCategory(['name' => $gICategory, 'category_custom_type' => 'GENRE_ITEM', 'parent_id' => $gCatId ?: $dataArray['genre_old']]);
        }
        $imagePath = $this->_loadImage($dataArray['work_id'], $dataArray['artist_id']);

        $productData = [
            'name' => $dataArray['title'],
            'sku'  => $dataArray['sku'],
            'price' => $dataArray['price'],
            'parent_cat' => $gICategory,
            'image_path' => $imagePath
        ];

        $productId = $this->_getProductCreatorModel()->createProduct($productData);

        if ($productId) {
            $artistId = $dataArray['artist_id'];
            $workId = $dataArray['work_id'];
            $this->_getArtistModel()->saveArtistProduct($artistId, $productId, $workId);
        }

        Mage::getSingleton('adminhtml/session')->addSuccess(
            'Saved!'
        );
        $this->_redirectReferer($this->getUrl('adminhtml/artist_workshop/editCreated') . 'id/' . $dataArray['work_id']);
    }

    public function editCreatedAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    protected function _loadImage($workId, $customerId)
    {
        $type = 'ws_image';
        if (isset($_FILES[$type]['name']) && $_FILES[$type]['name'] != '') {
            try {
                $uploader = new Varien_File_Uploader($type);
                $uploader
                    ->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);
                $path = Mage::getBaseDir('media') . DS . 'workshop/admin_images/' . $workId . '/' . $customerId . '/';
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                $uploader->save($path, $_FILES[$type]['name']);
                return Mage::getBaseDir('media') . DS . 'workshop/admin_images/' . $workId . '/' . $customerId . '/' . $uploader->getUploadedFileName();
            } catch (Exception $e) {

            }
        }
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