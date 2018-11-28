<?php
/**
 * Essive
 */

class Figures_Artist_Block_Adminhtml_Workshop_Forms_Created extends Figures_Artist_Block_Adminhtml_Workshop_Forms_Abstract
{
    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->setHtmlId('analog_parent_created_products_form');
        $this->setTemplate('artist/created_products.phtml');

        $this->_rowId = $this->getRequest()->getParam('id');
        $this->_initRowData();
    }

    public function _getFormsInfo()
    {
        $productData = $this->_getArtistModel()->getProductDataByWorkId($this->_rowId);
        $return = [];

        foreach ($productData as $productItem) {
            $productId = $productItem['product_id'];
            $product = Mage::getModel('catalog/product')->setStoreId(1)->load($productId);
            $url = $product->getProductUrl();
            $return[] = [
                'product_id'    => $productId,
                'name'          => $product->getName(),
                'sku'           => $product->getSku(),
                'edit_link'     => Mage::helper("adminhtml")->getUrl("adminhtml/catalog_product/edit/", array("id" => $productId)),
                'frontend_link' => $url,
                'qty_ordered'   => $this->_getArtistModel()->getSummaryOrderedForProduct($productId) ?: 0
            ];
        }

        return $return;
    }

    /**
     * @return Varien_Db_Adapter_Pdo_Mysql
     */
    protected function _getConnection()
    {
        return Mage::getModel('core/resource')->getConnection('core_write');
    }
}