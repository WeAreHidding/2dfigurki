<?php
class Figures_Catalog_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function getNoImage()
    {
        return Mage::getBaseDir('media') . DS. 'no-image.png';
    }
}