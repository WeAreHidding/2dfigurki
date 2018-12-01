<?php
/**
 *  Essive
 */
class Figures_Artist_Model_Cron extends Varien_Object
{
    public function calculateComissions()
    {
        Mage::log('crontest' . date('Y-m-d H:i:s'));
    }
}