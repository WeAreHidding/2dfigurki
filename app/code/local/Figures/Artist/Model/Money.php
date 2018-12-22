<?php
/**
 * Essive
 */
class Figures_Artist_Model_Money extends Mage_Core_Model_Abstract
{
    /**
     * @var Varien_Db_Adapter_Pdo_Mysql
     */
    protected $_connection = null;

    protected function _construct()
    {
        $this->_connection = $this->_getConnection();
        parent::_construct();
    }

    public function addMoney($artistId, $count)
    {

    }

    public function removeMoney($artistId, $count)
    {

    }

    /**
     * @param $artistId
     * @throws Zend_Db_Adapter_Exception
     */
    public function setStartMoney($artistId)
    {
        $this->_connection->insert('artist_money', ['artist_id' => $artistId]);
    }

    public function logMoneyChange($artistId, $value)
    {

    }

    /**
     * @return Varien_Db_Adapter_Pdo_Mysql
     */
    protected function _getConnection()
    {
        return Mage::getModel('core/resource')->getConnection('core_write');
    }
}