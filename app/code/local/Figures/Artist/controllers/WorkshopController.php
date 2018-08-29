<?php

class Figures_Artist_WorkshopController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function saveAction()
    {
        $model = $this->_getArtistModel();
        $params = $this->getRequest()->getParams();
        foreach ($params as $key => $value) {
            if ($key == 'ws_image') {
                continue;
            }
            $model->setData($key, $value);
        }

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
                $path = Mage::getBaseDir('media') . DS . 'media/workshop/user_images/' . $customerId . '/';
                if (!is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                $uploader->save($path, $_FILES[$type]['name']);
                $filename = $uploader->getUploadedFileName();
                $model->setData('image_path', $filename);
            } catch (Exception $e) {
                var_dump($e); die();
            }
        }

        $model->save();

        $this->_redirectReferer();
    }

    /**
     * @return Figures_Artist_Model_Artist
     */
    protected function _getArtistModel()
    {
        return Mage::getModel('figures_artist/artist');
    }
}