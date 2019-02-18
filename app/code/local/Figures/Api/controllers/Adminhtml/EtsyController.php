<?php

class Figures_Api_Adminhtml_EtsyController extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
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
        echo '<pre>';
        print_r($response);
        echo '<pre>';die();

        if (!empty($response['results'])) {
            $filepath = $this->_prepareCsv($response['results'], $methodName);
            $this->_prepareDownloadResponse(basename($filepath), array('type' => 'filename', 'value' => $filepath));
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                'No API response'
            );
        }
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