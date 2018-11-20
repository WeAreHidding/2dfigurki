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
            if ($key != 'id') {
                $formCategories[] = $key;
            }
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
        $datasArray = [];
        try {
            $datasArray = $this->_prepareAndValidateParamsForProduct($this->getRequest()->getParams());
            if (!$datasArray) {
                $this->_redirectReferer($this->getUrl('adminhtml/artist_workshop/editCreated') . 'id/' . $datasArray['additional_info']['work_id']);

                return;
            }
            foreach ($datasArray as $key => $dataArray) {
                if ($key == 'additional_info') {
                    continue;
                }
                $genreToCreate = $gIToCreate = $gCatId = true;
                $fCatId = $key;

                if (!$genreCategory = $dataArray['genre']) {
                    $genreCategory = $dataArray['genre_old'];
                    $genreToCreate = false;
                }
                if (!$gICategory = $dataArray['genre_item']) {
                    $gICategory = $dataArray['genre_item_old'];
                    $gIToCreate = false;
                }

                if ($genreToCreate) {
                    $gCatId = $this->_getProductCreatorModel()->createCategory(['name' => $genreCategory, 'category_custom_type' => 'GENRE', 'parent_id' => $fCatId ?: $dataArray['genre_item_old']]);
                }
                if ($gIToCreate) {
                    $this->_getProductCreatorModel()->createCategory(['name' => $gICategory, 'category_custom_type' => 'GENRE_ITEM', 'parent_id' => $gCatId ?: $dataArray['genre_old']]);
                }
                $imagePath = $this->_loadImage($datasArray['additional_info']['work_id'], $datasArray['additional_info']['artist_id'], $fCatId);

                $productData = [
                    'name' => $dataArray['title'],
                    'sku' => $dataArray['sku'],
                    'price' => $dataArray['price'],
                    'parent_cat' => $gICategory,
                    'image_path' => $imagePath
                ];

                $productId = $this->_getProductCreatorModel()->createProduct($productData);

                if ($productId) {
                    $artistId = $datasArray['additional_info']['artist_id'];
                    $workId = $datasArray['additional_info']['work_id'];
                    $this->_getArtistModel()->saveArtistProduct($artistId, $productId, $workId, null, $fCatId);
                }
            }
            Mage::getSingleton('adminhtml/session')->addSuccess(
                'Saved!'
            );
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
        $isValid = true;
        $invalidMessage = '';

        if ($dataByFormCategory) {
            foreach ($dataByFormCategory as $key => $items) {
                if ($key == 'additional_info') {
                    continue;
                }

                if (!$_FILES['ws_image_' . $key]['name']) {
                    $isValid = false;
                    $invalidMessage = 'Please load image for FORM #' . $key;
                    break;
                }
                
                if (!$items['sku'] || !$items['title'] || !$items['price']) {
                    $isValid = false;
                    $invalidMessage = 'Please fill data for FORM #' . $key;
                    break;
                }
                if ($items['genre'] && $items['genre_item']) {
                    continue;
                } elseif ($items['genre_old'] && $items['genre_item_old']) {
                    continue;
                }

                $isValid = false;
                $invalidMessage = 'Please fill data for FORM #' . $key;
                break;
            }
        }

        if (!$isValid) {
            Mage::getSingleton('adminhtml/session')->addError(
                $invalidMessage
            );
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

    public function getFandomCategoriesAction()
    {
        $genreId = $this->getRequest()->getParam('genre_cat_id');

        $categoryData = [];
        $categories = Mage::getModel('catalog/category')
            ->getCollection()
            ->addAttributeToSelect('*')
            ->addFieldToFilter('category_custom_type', 'GENRE_ITEM')
            ->addIsActiveFilter();

        $categories->addFieldToFilter('parent_id', $genreId);

        foreach ($categories as $category) {
            $categoryData[] = [
                'name' => $category->getName(),
                'id'   => $category->getId()
            ];
        }

        $this->getResponse()->setBody(json_encode($categoryData));
    }

    public function deleteProductAction()
    {
        $productId = $this->getRequest()->getParam('product_id');

        $this->_getConnection()->query("DELETE FROM catalog_product_entity WHERE entity_id = {$productId}");
        $this->_getConnection()->query("DELETE FROM artist_product WHERE product_id = {$productId}");
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