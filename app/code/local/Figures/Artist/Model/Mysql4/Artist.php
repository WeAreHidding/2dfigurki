<?php
/**
 * Essive
 */
class Figures_Artist_Model_Mysql4_Artist extends Mage_Core_Model_Mysql4_Abstract
{
    /**
     * Standard resource model initialization
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('figures_artist/artist', 'id');
    }
}