<?php

class Figures_Artist_Adminhtml_Artist_WorkshopController extends Mage_Adminhtml_Controller_Action
{
    protected $_generalParams = [
        'form_key',
        'description',
        'tags',
        'char_name',
        'main_tag'
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
            if ($key != 'id') {
                $formCategories[] = $key;
            }
        }
        if ($formCategories) {
            $formCategories = implode(',', $formCategories);
        }

        $connection = $this->_getConnection();

        $connection->update('artist_work',
            [
                'char_name'              => $params['char_name'],
                'description'            => $params['description'],
                'main_tag'               => $params['main_tag'],
                'tags'                   => $params['tags'],
                'proposed_form_category' => $formCategories
            ],
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
        $datasArray = [];
        try {
            $datasArray = $this->_prepareAndValidateParamsForProduct($this->getRequest()->getParams());
            if (!$datasArray) {
                $this->_redirectReferer($this->getUrl('adminhtml/artist_workshop/editCreated') . 'id/' . $datasArray['additional_info']['work_id']);

                return;
            }
            $productsQty = $datasArray['additional_info']['created_products_qty'];
            foreach ($datasArray as $key => $dataArray) {
                if ($key == 'additional_info') {
                    continue;
                }
                $fCatId = $key;

                if ($dataArray['genre']) {
                    $genreId = $this->_getProductCreatorModel()->createCategory(['name' => $dataArray['genre'], 'parent_id' => CustomEntities::GENRE_ID]);
                } else {
                    $genreId = $dataArray['genre_old'];
                }
                if ($dataArray['genre_item']) {
                    $fandomId = $this->_getProductCreatorModel()->createCategory(['name' => $dataArray['genre_item'], 'parent_id' => CustomEntities::FANDOM_ID]);
                } else {
                    $fandomId = $dataArray['genre_item_old'];
                }
                $imagePath = $this->_loadImage($datasArray['additional_info']['work_id'], $datasArray['additional_info']['artist_id'], $fCatId);

                $productData = [
                    'name'            => $dataArray['title'],
                    'price'           => $dataArray['price'],
                    'parent_cats_ids' => [$fCatId, $genreId, $fandomId],
                    'image_path'      => $imagePath,
                    'main_tag'        => $dataArray['main_tag'],
                    'tags'            => $dataArray['tags'],
                    'description'     => $dataArray['description'],
                    'artist_id'       => $datasArray['additional_info']['artist_id'],
                    'form_cat_id'     => $key,
                    'work_id'         => $datasArray['additional_info']['work_id']
                ];

                $productId = $this->_getProductCreatorModel()->createProduct($productData);

                if ($productId) {
                    $artistId = $datasArray['additional_info']['artist_id'];
                    $workId   = $datasArray['additional_info']['work_id'];
                    $this->_getArtistModel()->saveArtistProduct($artistId, $productId, $workId, null, $fCatId);
                    $productsQty++;
                    $this->_getConnection()->update('artist_work', ['created_products_qty' => $productsQty], 'id=' . $datasArray['additional_info']['work_id']);
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    'Product defined in form # ' . $fCatId . 'is saved!'
                );
            }
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError(
                $e->getMessage()
            );
            $this->_redirectReferer($this->getUrl('adminhtml/artist_workshop/editCreated') . 'id/' . $datasArray['additional_info']['work_id']);
        }

        $this->_redirectReferer($this->getUrl('adminhtml/artist_workshop/editCreated') . 'id/' . $datasArray['additional_info']['work_id']);
    }

    protected function _prepareAndValidateParamsForProduct($params)
    {
        $proposedFormCategories = $this->_getProposedFormCategories($params['work_id']);
        $dataByFormCategory = [];
        foreach ($proposedFormCategories as $proposedFormCategory) {
            foreach ($params as $key => $param) {
                if(stristr($key, $proposedFormCategory) !== FALSE) {
                    unset($params[$key]);
                    unset($dataByFormCategory['additional_info'][$key]);
                    $key = str_replace('_' . $proposedFormCategory, '', $key);
                    $dataByFormCategory[$proposedFormCategory][$key] = $param;
                } else {
                    $dataByFormCategory['additional_info'][$key] = $param;
                }
            }
        }

        //validation
        $invalidMessage = '';
        if ($dataByFormCategory) {
            foreach ($dataByFormCategory as $key => $items) {
                if ($key == 'additional_info') {
                    continue;
                }

                if (!$_FILES['ws_image_' . $key]['name']) {
                    $invalidMessage = 'Please load image for FORM #' . $key;
                    unset($dataByFormCategory[$key]);
                    continue;
                }
                
                if (!$items['title'] || !$items['price'] ||
                    !$items['main_tag'] || !$items['tags'] || !$items['description']) {
                    $invalidMessage = 'Please fill data for FORM #' . $key;
                    unset($dataByFormCategory[$key]);
                    continue;
                }
                if (($items['genre'] || $items['genre_old']) && ($items['genre_item'] || $items['genre_item_old'])) {
                    if ($items['genre']) {
                        if (!$this->_getProductCreatorModel()->validateSpecificAttributes('artist_genre', $items['genre'])) {
                            $invalidMessage = 'Such genre already exists for FORM #' . $key;
                            unset($dataByFormCategory[$key]);
                            continue;
                        }
                    }
                    if ($items['genre_item']) {
                        if (!$this->_getProductCreatorModel()->validateSpecificAttributes('artist_fandom', $items['genre_item'])) {
                            $invalidMessage = 'Such fandom already exists for FORM #' . $key;
                            unset($dataByFormCategory[$key]);
                            continue;
                        }
                    }
                    continue;
                }

                $invalidMessage = 'Please fill data for FORM #' . $key;
                unset($dataByFormCategory[$key]);
                continue;
            }
        }

        if ($invalidMessage) {
            Mage::getSingleton('adminhtml/session')->addError(
                $invalidMessage
            );
        }
        if (!$dataByFormCategory) {
            return false;
        }

        return $dataByFormCategory;
    }

    protected function _loadImage($workId, $customerId, $formCatId)
    {
        $type = 'ws_image_' . $formCatId;
        if (isset($_FILES[$type]['name']) && $_FILES[$type]['name'] != '') {
            $uploader = new Varien_File_Uploader($type);
            $uploader
                ->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $path = Mage::getBaseDir('media') . DS . 'workshop/admin_images/' . $workId . '/' . $customerId . '/' . $formCatId . '/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            $uploader->save($path, $_FILES[$type]['name']);

            return Mage::getBaseDir('media') . DS . 'workshop/admin_images/' . $workId . '/' . $customerId . '/' . $formCatId . '/' . $uploader->getUploadedFileName();
        }

        return false;
    }

    public function editCreatedAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    protected function _getProposedFormCategories($workId)
    {
        $connection = $this->_getConnection();
        $pfc = $connection->fetchOne($connection->select()->from('artist_work', 'proposed_form_category')->where('id = ?', $workId));

        return explode(',', $pfc);
    }

    public function deleteProductAction()
    {
        $productId = $this->getRequest()->getParam('product_id');
        $workId = $this->getRequest()->getParam('work_id');
        $newQty = $this->getRequest()->getParam('new_qty');

        $this->_getConnection()->query("DELETE FROM catalog_product_entity WHERE entity_id = {$productId}");
        $this->_getConnection()->query("DELETE FROM artist_product WHERE product_id = {$productId}");
        $this->_getConnection()->query("UPDATE artist_work SET created_products_qty = {$newQty} WHERE id = {$workId}");
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