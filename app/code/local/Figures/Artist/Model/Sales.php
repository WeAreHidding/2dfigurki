<?php
/**
 * Essive
 */
class Figures_Artist_Model_Sales extends Mage_Core_Model_Abstract
{
    public function saveArtistSoldItem($data)
    {
        $this->_getConnection()->insert(
            'artist_sales',
            $data
        );
    }

    public function updateArtistSoldItem($data, $oiId)
    {
        $this->_getConnection()->update(
            'artist_sales',
            $data,
            'order_item_id =' . $oiId
        );
    }

    public function getSales($bind = '', $getProductLink = false)
    {
        $connection = $this->_getConnection();

        $salesData = $connection->fetchAll(
            $connection->select()
                ->from('artist_sales')
                ->where($bind)
        );
        if ($getProductLink) {
            foreach ($salesData as $key => $item) {
                $product = Mage::getModel('catalog/product')->setStoreId(1)->load($item['product_id']);
                $salesData[$key]['product_url'] = $product->getProductUrl();
            }
        }

        return $salesData;
    }

    public function getCollection()
    {
        $connection = $this->_getConnection();
        $collection = new Varien_Data_Collection();
        $data = $connection->fetchAll(
            $connection->select()->from('artist_sales')
        );
        if (!$data) {
            return $collection;
        }

        foreach ($data as $item) {
            $collectionItem = new Varien_Object();
            foreach ($item as $key => $value) {
                $collectionItem->setData($key, $value);
            }

            $collection->addItem($collectionItem);
        }

        return $collection;
    }

    /**
     * @return Varien_Db_Adapter_Pdo_Mysql
     */
    protected function _getConnection()
    {
        return Mage::getModel('core/resource')->getConnection('core_write');
    }
}