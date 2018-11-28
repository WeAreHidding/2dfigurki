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

        return $category->getId();
    }

    public function createProduct($productData)
    {
        $product = new Mage_Catalog_Model_Product();

        // Build the product
        $product->setSku($this->getSku($productData['form_cat_id'], $productData['work_id']));
        $product->setName($productData['name']);
        $product->setPrice($productData['price']);
        $product->setDescription($productData['description']);
        $product->setShortDescription($productData['description']);
        $product->setMainTag($productData['main_tag']);
        $product->setTags($productData['tags']);
        $product->setArtistId($productData['artist_id']);

        $category = Mage::getResourceModel('catalog/category_collection')
            ->addFieldToFilter('name', $productData['parent_cat'])
            ->getFirstItem();
        if (!$categoryId = $category->getId()) {
            $categoryId = $productData['parent_cat'];
        }

        $product->setAttributeSetId(4);
        $product->setTypeId('simple');
        $product->setCategoryIds(array($categoryId));
        $product->setWebsiteIDs(array(1));
        $product->setWeight(4.0000);
        $product->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH);
        $product->setStatus(1);
        $product->setTaxClassId(0);
        $product->setStockData(array(
            'is_in_stock' => 1,
            'qty' => 99999
        ));
        $product->setCreatedAt(strtotime('now'));

        //load image
        $filePath = $productData['image_path'];
        if (file_exists($filePath)) {
            $product->addImageToMediaGallery($filePath, array('image', 'small_image', 'thumbnail'), false, false);
        }

        try {
            $product->save();

            return $product->getId();
        }
        catch (Exception $ex) {
            print_r("Critical during product creation! Contact developer\n\n");
            print_r($ex); die();
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

    public function getSku($formCategoryId, $workId)
    {
        $category = Mage::getModel('catalog/category')
            ->getCollection()
            ->addAttributeToSelect('*')
            ->addFieldToFilter('category_custom_type', 'FORM')
            ->addFieldToFilter('entity_id', $formCategoryId)
            ->addIsActiveFilter()
            ->getFirstItem();
        if (!$category->getName()) {
            die('Critical error during sku creating');
        }
        if (!$prefix = $category->getSkuPrefix()){
            $prefix = substr($category->getName(), 0, 3);
        }

        return strtolower($prefix . $workId);
    }
}