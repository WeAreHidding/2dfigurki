<?php
/**
 * Essive
 */
class Figures_Artist_Model_Mysql4_Artist_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('figures_artist/artist');
    }
}