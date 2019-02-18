<?php
/**
 * Essive
 */

class Figures_Api_Block_Adminhtml_Etsy_Form extends Mage_Adminhtml_Block_Widget
{
    protected $_methods = [];
    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->setHtmlId('etsy_form');
        $this->setTemplate('a_api/etsy_form.phtml');
        $reconfigureUrl = $this->getUrl('adminhtml/etsy/request');

        if ($this->isConfigured()) {
            Mage::getSingleton('adminhtml/session')->addSuccess(
                'Etsy tokens is RECEIVED. <a href="' . $reconfigureUrl . '">Reconfigure</a>'
            );
        } else {
            Mage::getSingleton('adminhtml/session')->addError(
                'Etsy tokens is MISSED. <a href="' . $reconfigureUrl . '">Reconfigure</a>'
            );
        }
        $this->_methods = $this->_getConnector()->getMethods();

        if (empty($this->_methods) || !$this->_methods) {
            Mage::getSingleton('adminhtml/session')->addError(
                'Cannot parse methods file'
            );
        }
    }

    public function getAllMethods()
    {
        return $this->_methods;
    }

    public function getSelectedMethod()
    {
        if ($method = $this->getRequest()->getParam('method')) {
            return $method;
        } else {
            return false;
        }
    }

    public function getFieldsForSelectedMethod()
    {
        $methodData = $this->_methods[$this->getSelectedMethod()];
        $fields = [];
        if ($methodData) {
            foreach ($methodData['params'] as $param => $paramType) {
                $fields[$param] = !empty($methodData['defaults'][$param]) ? $methodData['defaults'][$param] : '';
            }
        }

        return $fields;
    }

    public function getMethodUrl($method)
    {
        return $this->getUrl('adminhtml/etsy/', array('method' => $method));
    }

    public function isConfigured()
    {
        return $this->_getOauth()->isConfigured();
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
     * @return Varien_Db_Adapter_Pdo_Mysql
     */
    protected function _getConnection()
    {
        return Mage::getModel('core/resource')->getConnection('core_write');
    }
}