<?php
/** Essive */
class  Figures_Catalog_Block_Product_View extends Mage_Catalog_Block_Product_View
{
    public function getPrice()
    {
        $price = $this->getProduct()->getPrice();
        $price = round($price, 2);
        $price = explode('.', $price);
        return ['dollars' => $price[0], 'cents' => $price[1]];
    }

    public function getArtist()
    {
        $customer = Mage::getModel('customer/customer')->load($this->getProduct()->getArtist_id());
        return $customer->getArtist_nickname();
    }

    public function getCategoryName()
    {
        $cat = Mage::getModel('catalog/category');
        $cat->load($this->getProduct()->getCategoryIds()[0]);
        return $cat->getName();
    }
}