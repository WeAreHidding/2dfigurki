<?php
/**
 * Essive
 */
class Figures_Artist_Model_Comission extends Mage_Core_Model_Abstract
{
    public function getArtistComission($artistId)
    {
        return $this->_getConnection()->fetchOne(
            $this->_getConnection()->select()
                ->from('artist_comission', 'value')
                ->where('artist_id = ?', $artistId)
        ) ?: 0;
    }

    /**
     * @return Varien_Db_Adapter_Pdo_Mysql
     */
    protected function _getConnection()
    {
        return Mage::getModel('core/resource')->getConnection('core_write');
    }
}