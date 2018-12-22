<?php
/**
 * Essive
 */

class Figures_Artist_Block_Adminhtml_Config_Config extends Mage_Adminhtml_Block_Widget
{
    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->setHtmlId('config_form');
        $this->setTemplate('artist/config.phtml');
    }

    public function getMinimalPercent()
    {
        return Mage::getStoreConfig('start_comission') ?: 20;
    }

    public function getTotalMoneyToPay()
    {
        return $this->_getConnection()->fetchOne("SELECT SUM(value) FROM artist_money") ?: 0;
    }

    /**
     * @return Varien_Db_Adapter_Pdo_Mysql
     */
    protected function _getConnection()
    {
        return Mage::getModel('core/resource')->getConnection('core_write');
    }
}