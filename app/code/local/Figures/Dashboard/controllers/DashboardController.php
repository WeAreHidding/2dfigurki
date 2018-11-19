<?php

class Figures_Dashboard_DashboardController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        if (!Mage::getSingleton('customer/session')->getCustomer()->getId()) {
            Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('customer/account'));
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    public function saveInfoAction()
    {
        $model = $this->_getArtistModel();
        $params = $this->getRequest()->getParams();
        $proposedFormCategories = [];
        foreach ($params as $key => $value) {
            if ($key == 'ws_image') {
                continue;
            }
            if(stristr($key, 'form_cat_') !== FALSE) {
                $input = explode('form_cat_', $key);
                $proposedFormCategories[] = $input[1];
                continue;
            }
            $model->setData($key, $value);
        }

        $model->setData('proposed_form_category', implode(',', $proposedFormCategories));

        $customerId = Mage::getSingleton('customer/session')->getCustomer()->getId();
        $model->setData('customer_id', $customerId);

        $type = 'ws_image';
        if (isset($_FILES[$type]['name']) && $_FILES[$type]['name'] != '') {
            try {
                $uploader = new Varien_File_Uploader($type);
                $uploader
                    ->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
                $uploader->setAllowRenameFiles(true);
                $uploader->setFilesDispersion(true);
                $path = Mage::getBaseDir('media') . DS . 'workshop/user_images/' . $customerId . '/';
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                $uploader->save($path, $_FILES[$type]['name']);
                $filename = $uploader->getUploadedFileName();
                $model->setData('image_path', $filename);
            } catch (Exception $e) {

            }
        }

        $model->save();
    }


    /**
     * @return Figures_Artist_Model_Artist
     */
    protected function _getArtistModel()
    {
        return Mage::getModel('figures_artist/artist');
    }
}