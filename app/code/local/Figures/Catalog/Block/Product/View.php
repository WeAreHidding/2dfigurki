<?php
/** Essive */
class  Figures_Catalog_Block_Product_View extends Mage_Catalog_Block_Product_View
{

    private function formatPrice($price)
    {
        $price = round($price, 2);
        $price = explode('.', $price);
        return ['dollars' => $price[0], 'cents' => $price[1]];
    }

    public function getPrice()
    {
        return $this->formatPrice($this->getProduct()->getPrice());
    }

    public function getArtist()
    {
        $customer = Mage::getModel('customer/customer')->load($this->getProduct()->getArtist_id());
        return $customer->getArtist_nickname();
    }

    public function getCategoryName()
    {
        $cats_ids = $this->getProduct()->getCategoryIds();
        $catnames = '';
        foreach($cats_ids as $cat_id){
            $cat = Mage::getModel('catalog/category');
            $cat->load($cat_id);
            if(!empty($catnames)){
                $catnames .= ' '.$cat->getName();
            } else {
                $catnames .= $cat->getName();
            }

        }
        return $catnames;
    }

    public function getArtistsOtherProducts()
    {
        $collection = Mage::getModel('catalog/product')->getCollection();
        $collection->addAttributeToSelect('name');
        $collection->addAttributeToSelect('price');
        $collection->addAttributeToSelect('small_image');

        $collection->addFieldToFilter(array(
            array('attribute'=>'artist_id','eq'=>$this->getProduct()->getArtist_id()),
        ));
        $collection->addFieldToFilter(array(
            array('attribute'=>'entity_id','neq'=>$this->getProduct()->getId()),
        ));
        $html = '';

        foreach ($collection as $product) {

            $data = $product->getData();

            $pricedata = $this->formatPrice($data['price']);

            $price = $pricedata['dollars'];

            if($pricedata['cents']){
                $price .= '<sup>'.$price['cents'].'</sup>';

            }

            $html .= '
            
             <div class="item">
                <img src="/media/catalog/product'.$data['small_image'].'" alt="img_design">
                <h3>'.$data['name'].'</h3>
                <span>$'.$price.'</span>
                <div class="product-rating">
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star checked"></span>
                    <span class="fa fa-star"></span>
                    <span class="fa fa-star"></span>
                </div>
            </div>
            
            ';
        }

        return $html;

    }
}