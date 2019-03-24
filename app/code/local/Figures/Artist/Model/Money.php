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

    public function addMoney($artistId, $count, $description)
    {
        $currentMoney = $this->getArtistMoney($artistId);
        $this->_connection->update('artist_money', ['value' => $currentMoney + $count], 'artist_id = ' . $artistId);
        $this->logMoneyChange($artistId, '+' . $count, $description);
    }

    public function removeMoney($artistId, $count, $description)
    {
        $currentMoney = $this->getArtistMoney($artistId);
        $this->_connection->update('artist_money', ['value' => $currentMoney - $count], 'artist_id = ' . $artistId);
        $this->logMoneyChange($artistId, '-' . $count, $description);
    }

    /**
     * @param $artistId
     * @throws Zend_Db_Adapter_Exception
     */
    public function setStartMoney($artistId)
    {
        $this->_connection->insert('artist_money', ['artist_id' => $artistId]);
    }

    public function logMoneyChange($artistId, $value, $description)
    {
        $this->_connection->update('artist_money_log', ['value' => $value, 'description' => $description], 'artist_id = ' . $artistId);
    }

    public function getArtistMoney($artistId)
    {
        return $this->_connection->fetchOne($this->_connection->select()->from('artist_money', 'value')->where('artist_id = ?', $artistId));
    }

    public function getMoneyLogs($artistId)
    {
        $connection = $this->_getConnection();

        $log = $connection->fetchAll(
            $connection->select()
                ->from('artist_money_log')
                ->where('artist_id = ?', $artistId)
        );

        return $log;
    }

    /**
     * @return Varien_Db_Adapter_Pdo_Mysql
     */
    protected function _getConnection()
    {
        return Mage::getModel('core/resource')->getConnection('core_write');
    }
}