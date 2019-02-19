<?php

class Figures_Api_Adminhtml_EtsyController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    public function indexUpdateAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * @throws Zend_Db_Adapter_Exception
     */
    public function requestAction()
    {
        $link = $this->_getOauth()->request();
        if (!empty($link['error'])) {
            echo '<span style="color:red">' . $link['message'] . '</span>';
            return;
        }

        print "Please connect with Etsy <a href=$link onclick='close();' target=_blank>here</a>";
    }

    /**
     * @throws Zend_Db_Adapter_Exception
     */
    public function callbackAction()
    {
        $response = $this->_getOauth()->setTokens();
        if ($response !== 'ok') {
            echo '<span style="color:red">' . $response['message'] . '</span>';
        } else {
            echo '<span style="color:green">' . 'New tokens configured' . '</span>';;
        }
    }

    public function callAction()
    {
        $params = $this->getRequest()->getParams();
        unset($params['form_key']);
        $methodName = $params['methods_select'];
        unset($params['methods_select']);

        $response = $this->_getConnector()->call($methodName, $params);
//        echo '<pre>';
//        print_r($response);
//        echo '<pre>';die();

        if (!empty($response['results'])) {
            $filepath = $this->_prepareCsv($response['results'], $methodName);
            $this->_prepareDownloadResponse(basename($filepath), array('type' => 'filename', 'value' => $filepath));
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                'No API response'
            );
        }
    }

    public function callUpdateAction()
    {
        $params = $this->getRequest()->getParams();
        unset($params['form_key']);
        $listingId = $params['listing_id'];
        unset($params['listing_id']);

        $report = $this->_getConnector()->callUpdate($listingId, $params);
        if (!empty($report['ok'])) {
            Mage::getSingleton('adminhtml/session')->addSuccess(
                $report['ok']
            );
        }
        if (!empty($report['error'])) {
            Mage::getSingleton('adminhtml/session')->addError(
                $report['error']
            );
        }

        $this->_redirectReferer($this->getUrl('adminhtml/etsy/indexUpdate'));
    }

    public function callUpdateCsvAction()
    {
        $fileName = $this->_saveCsv();
        var_dump($fileName);
    }

    protected function _saveCsv()
    {
        var_dump($_FILES);
        $type = 'etsy_csv';
        if (isset($_FILES[$type]['name']) && $_FILES[$type]['name'] != '') {
            $uploader = new Varien_File_Uploader($type);
            $uploader
                ->setAllowedExtensions(array('csv'));
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $path = Mage::getBaseDir('media') . DS . 'api/etsy_upload/';
            if (!is_dir($path)) {
                mkdir($path, 0777, true);
            }
            $uploader->save($path, $_FILES[$type]['name']);

            return $path . $uploader->getUploadedFileName();
        }

        return false;
    }

    protected function _prepareCsv($dataArray, $methodName)
    {
        $dir = Mage::getBaseDir('media') . DS . 'api/';
        $filename =  $methodName . date('Y-m-d')  . '.csv';
        $fp = fopen($dir . DS . $filename, 'w');

        array_unshift($dataArray, array_keys($dataArray[0]));
        foreach ($dataArray as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);

        return $dir . DS . $filename;
    }

    /**
     * @return Figures_Api_Model_Etsy_Connector
     */
    protected function _getConnector()
    {
        return Mage::getModel('figures_api/etsy_connector');
    }


    /**
     * @return Figures_Api_Model_Etsy_Oauth
     */
    protected function _getOauth()
    {
        return Mage::getModel('figures_api/etsy_oauth');
    }
}