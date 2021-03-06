<?php

require_once Mage::getModuleDir('controllers', 'Mage_Catalog').DS.'CategoryController.php';

class Figures_Catalog_CategoryController extends Mage_Catalog_CategoryController
{
    public function loadProductsAction()
    {
        $id = $this->getRequest()->getParam('category_id');
        $filters = $this->getRequest()->getParam('filters');
        $sort = $this->getRequest()->getParam('sort');
        $page = $this->getRequest()->getParam('page') ?: 1;
        $data = [];

        if ($id && $sort) {
            $checkoutHelper = Mage::helper('checkout/cart');
            $cartItemsIds = $this->_getCartItemsProductIds();
            $category = Mage::getModel('catalog/category')->load($id);

            $productCollection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('*')
                ->addCategoryFilter($category);

            $productCollection = $this->_getCategoryFiltersHelper()->filterCollection($id, $productCollection, $filters, $sort, $page);

            foreach ($productCollection as $product) {
                $data[] = [
                    'name'        => $product->getName(),
                    'url'         => $product->getProductUrl(),
                    'description' => $product->getShortDescription(),
                    'price'       => number_format($product->getPrice(), 2),
                    'image'       => $product->getImageUrl(),
                    'in_cart'     => in_array($product->getId(), $cartItemsIds),
                    'add_url'     => $checkoutHelper->getAddUrl($product)
                ];
            }
        }

        $html = $this->_prepareHtml($data);
        $this->getResponse()->setBody($html);
    }

    public function getCartItemsCountAction()
    {
        $count = (Mage::helper('checkout/cart')->getSummaryCount()) ? Mage::helper('checkout/cart')->getSummaryCount() : 0;
        $this->getResponse()->setBody($count);
    }

    public function getPagesCountAction()
    {
        $id = $this->getRequest()->getParam('category_id');
        $filters = $this->getRequest()->getParam('filters');
        $pagesCount = 1;

        if ($id && $filters) {
            $category = Mage::getModel('catalog/category')->load($id);

            $productCollection = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToSelect('*')
                ->addCategoryFilter($category);
            $productCollection = $this->_getCategoryFiltersHelper()->filterCollection($id, $productCollection, $filters);

            $pagesCount = $productCollection->getSize() / Figures_Catalog_Helper_CategoryFilters::PRODUCTS_PER_PAGE;
        }

        $this->getResponse()->setBody($pagesCount);
    }

    protected function _prepareHtml($data)
    {
        if (!$data) {
            return '<span>The are no products matching your selection</span>';
        }
        $html = '';
        foreach ($data as $item) {
            $html .= '<div class="category-product-card"><div class="category-product-image">';
            $html .= '<a href="' . $item['url'] . '"><img src="' . $item['image'] . '"></a></div><div class="category-product-info">';
            $html .= '<h5>$' . $item['price'] . '</h5>';
            $html .= '<h6>' . $item['name'] . '</h6>';
            if ($item['in_cart']) {
                $html .= '<button class="btn btn-outline-info"><i class="fa fa-check"></i> <i class="fa fa-shopping-bag"></i></button>';
            } else {
                $html .= '<button class="btn btn-info" onclick="addToCart(this,\''. $item['add_url'] . '\')"><i class="fa fa-plus"></i> <i class="fa fa-shopping-basket"></i></button>';
            }
            $html .= '</div></div>';

        }

        return $html;
    }

    protected function _getCartItemsProductIds()
    {
        $items = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
        $itemsProductIds = array_map(function($item) { return $item->getProductId(); }, $items);

        return $itemsProductIds;
    }

    /**
     * @return Figures_Catalog_Helper_CategoryFilters
     */
    protected function _getCategoryFiltersHelper()
    {
        return Mage::helper('figures_catalog/categoryFilters');
    }
}
