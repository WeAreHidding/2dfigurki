<?php
/**
 * Essive
 */
class Figures_Artist_Model_Sales extends Mage_Core_Model_Abstract
{
    public function saveArtistSoldItem($data)
    {
        Mage::log($data);
        $this->_getConnection()->insert(
            'artist_sales',
            $data
        );
    }

    public function updateArtistSoldItem()
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