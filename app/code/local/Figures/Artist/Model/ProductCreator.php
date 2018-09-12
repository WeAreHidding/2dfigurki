<?php
/**
 * Essive
 */
class Figures_Artist_Model_ProductCreator extends Mage_Core_Model_Abstract
{
    /**
     * @param $categoryData
     *
     * @return Exception|string
     */
    public function createCategory($categoryData)
    {
        Mage::app("admin");
        $parentId = $categoryData['parent_id'] ?: 2;
        $url = $this->formatUrlKey($categoryData['name']);
        try{
            $category = Mage::getModel('catalog/category');
            $category->setName($categoryData['name']);
            $category->setUrlKey($url);
            $category->setIsActive(1);
            $category->setDisplayMode(Mage_Catalog_Model_Category::DM_PRODUCT);
            $category->setIsAnchor(1); //for active achor
            $category->setStoreId(Mage::app()->getStore()->getId());
            $parentCategory = Mage::getModel('catalog/category')->load($parentId);
            $category->setPath($parentCategory->getPath());
            $category->setcategory_custom_type($categoryData['category_custom_type']);
            $category->save();
        } catch(Exception $e) {
            return $e;
        }

        return 'ok';
    }

    public function createProduct($productData)
    {
//$product = Mage::getModel('catalog/product');
        $product = new Mage_Catalog_Model_Product();
// Build the product
        $product->setSku($productData['sku']);
        $product->setAttributeSetId(4);
        $product->setTypeId('simple');
        $product->setName($productData['name']);
        $category = Mage::getResourceModel('catalog/category_collection')
            ->addFieldToFilter('name', $productData['parent_cat'])
            ->getFirstItem(); // The parent category


        $product->setCategoryIds(array($category->getId())); # some cat id's, my is 7
        $product->setWebsiteIDs(array(1)); # Website id, my is 1 (default frontend)
//        $product->setDescription('Full description here');
//        $product->setShortDescription('Short description here');
        $product->setPrice($productData['price']); # Set some price
# Custom created and assigned attributes
//        $product->setHeight('my_custom_attribute1_val');
//        $product->setWidth('my_custom_attribute2_val');
//        $product->setDepth('my_custom_attribute3_val');
//        $product->setType('my_custom_attribute4_val');
//Default Magento attribute
        $product->setWeight(4.0000);
        $product->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
        $product->setStatus(1);
        $product->setTaxClassId(0); # My default tax class
        $product->setStockData(array(
            'is_in_stock' => 1,
            'qty' => 99999
        ));
        $product->setCreatedAt(strtotime('now'));
        try {
            $product->save();
        }
        catch (Exception $ex) {
            //Handle the error
        }
    }

    /**
     * Format URL key from name or defined key
     *
     * @param string $str
     * @return string
     */
    public function formatUrlKey($str)
    {
        $str = Mage::helper('catalog/product_url')->format($str);
        $urlKey = preg_replace('#[^0-9a-z]+#i', '-', $str);
        $urlKey = strtolower($urlKey);
        $urlKey = trim($urlKey, '-');
        return $urlKey;
    }

    public function isCategoryExists($categoryName)
    {

    }
}