<?php
/** Essive */
class Figures_Catalog_Block_Product_View extends Mage_Catalog_Block_Product_View
{
    public function getPrice()
    {
        $price = $this->getProduct()->getPrice();
        $price = round($price, 2);
        $price = explode('.', $price);
        return ['dollars' => $price[0], 'cents' => $price[1]];
    }
}