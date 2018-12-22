<?php
/**
 * Essive
 */
class Figures_Artist_Model_Comission extends Mage_Core_Model_Abstract
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

    /**
     * @param $artistId
     * @return int
     */
    public function getArtistComission($artistId)
    {
        return $this->_connection->fetchOne(
            $this->_connection->select()
                ->from('artist_comission', 'value')
                ->where('artist_id = ?', $artistId)
        ) ?: 0;
    }

    /**
     * @param $artistId
     * @param $value
     * @throws Zend_Db_Adapter_Exception
     */
    public function setArtistComission($artistId, $value)
    {
        $this->_connection->update('artist_comission', ['value' => $value], 'artist_id = ' . $artistId);
    }

    /**
     * @param $artistId
     * @throws Zend_Db_Adapter_Exception
     */
    public function setStartComission($artistId)
    {
        $value = $this->_getDefaultComission();
        $this->_connection->insert('artist_comission', ['artist_id' => $artistId, 'value' => $value]);
        $this->logComissionChange($artistId, '+' . $value, 'Basic percent');
    }

    /**
     * @param $artistId
     * @param $value
     * @param $description
     * @throws Zend_Db_Adapter_Exception
     */
    public function logComissionChange($artistId, $value, $description)
    {
        $this->_connection->update('artist_comission_log', ['value' => $value, 'description' => $description], 'artist_id = ' . $artistId);
    }

    /**
     * @return void
     */
    public function recalculateComissionForArtists()
    {
        $artists = $this->_connection->fetchCol(
            $this->_connection->select()
                ->from('artist_comission', 'artistId')
        );
        $date = date('Y-m-d H:i:s');
        $twoWeeksAgo = date('Y-m-d H:i.s', strtotime('-2 week'));
        $monthAgo = date('Y-m-d H:i.s', strtotime('-2 month'));


        var_dump($monthAgo);
        var_dump($twoWeeksAgo);
        die();
        foreach ($artists as $artistId) {

        }
    }

    /**
     * @return int
     */
    protected function _getDefaultComission()
    {
        return Mage::getStoreConfig('start_comission') ?: 20;
    }

    /**
     * @return Varien_Db_Adapter_Pdo_Mysql
     */
    protected function _getConnection()
    {
        return Mage::getModel('core/resource')->getConnection('core_write');
    }
}