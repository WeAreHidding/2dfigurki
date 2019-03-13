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

    public function indexCreateAction()
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
        $filepath = $this->_prepareCsv($response, $methodName);
        $this->_prepareDownloadResponse(basename($filepath), array('type' => 'filename', 'value' => $filepath));
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
        $filePath = $this->_saveCsv();
        $csvData = $this->_getCsvHelper()->csvToArray($filePath);

        $totalCount = count($csvData);
        $counter = 0;
        $sleepCounter = 0;
        foreach ($csvData as $item) {
            $listingId = $item['listing_id'];
            unset($item['listing_id']);
            $report = $this->_getConnector()->callUpdate($listingId, $item);
            if (!empty($report['error'])) {
                Mage::getSingleton('adminhtml/session')->addError(
                    $report['error']
                );
                break;
            }
            $counter++;
            $sleepCounter++;
            if ($sleepCounter == 5) {
                sleep(1);
                $sleepCounter = 0;
            }
        }

        Mage::getSingleton('adminhtml/session')->addNotice(
            "Processed $counter/$totalCount rows"
        );
    }

    public function callCreateAction()
    {
        $params = $this->getRequest()->getParams();
        unset($params['form_key']);

        $params['is_supply'] = 1;
        $report = $this->_getConnector()->call('createListing', $params);

        if (!empty($report['error'])) {
            Mage::getSingleton('adminhtml/session')->addError(
                $report['error']
            );
        } else {
            Mage::getSingleton('adminhtml/session')->addSuccess(
                'New product was created'
            );
        }
        var_dump($report); die();

        $this->_redirectReferer($this->getUrl('adminhtml/etsy/indexCreate'));
    }

    public function callCreateCsvAction()
    {
        $filePath = $this->_saveCsv();
        $csvData = $this->_getCsvHelper()->csvToArray($filePath);

        $totalCount = count($csvData);
        $counter = 0;
        $sleepCounter = 0;
        foreach ($csvData as $item) {
            $report = $this->_getConnector()->call('createListing', $item);
            if (!empty($report['error'])) {
                Mage::getSingleton('adminhtml/session')->addError(
                    $report['error']
                );
                break;
            }
            $counter++;
            $sleepCounter++;
            if ($sleepCounter == 5) {
                sleep(1);
                $sleepCounter = 0;
            }
        }

        Mage::getSingleton('adminhtml/session')->addNotice(
            "Processed $counter/$totalCount rows"
        );
    }

    protected function _saveCsv()
    {
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

    protected function _prepareCsv($response, $methodName)
    {
        $dir = Mage::getBaseDir('media') . DS . 'api/';
        $filename =  $methodName . date('Y-m-d')  . '.csv';

        if ($methodName == 'findAllShopListingsActive') {
            $dataArray = $response['results'];
        } else {
            echo '<pre>';print_r($response);echo '<pre>';die();
        }
        $path = $this->_getCsvHelper()->arrayToCsv($dataArray, $dir . DS . $filename);

        return $path;
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

    /**
     * @return Figures_Artist_Helper_Csv
     */
    protected function _getCsvHelper()
    {
        return Mage::helper('figures_artist/csv');
    }
}