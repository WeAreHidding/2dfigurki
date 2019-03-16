<?php
/**
 * Essive
 */
class Figures_Artist_Model_Sales extends Mage_Core_Model_Abstract
{
    const COMISSION_NOT_PAID = 'not_paid';
    const COMISSION_PAID     = 'paid';

    /**
     * @param $data
     * @throws Zend_Db_Adapter_Exception
     */
    public function saveArtistSoldItem($data)
    {
        $this->_getConnection()->insert(
            'artist_sales',
            $data
        );
    }

    /**
     * @param $data
     * @param $oiId
     * @throws Zend_Db_Adapter_Exception
     */
    public function updateArtistSoldItem($data, $oiId)
    {
        $this->_getConnection()->update(
            'artist_sales',
            $data,
            'order_item_id =' . $oiId
        );
    }

    public function getSales($bind = '1', $getProductLink = false)
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
                $salesData[$key]['product_name'] = $product->getName();
            }
        }

        return $salesData;
    }

    public function getMoneySumByBind($bind = '1')
    {
        $connection = $this->_getConnection();

        return $salesData = $connection->fetchOne(
            $connection->select()
                ->from('artist_sales', 'SUM(artist_comission_net) as sum')
                ->where($bind)
        ) ?: 0.00;
    }

    public function getSoldQtySumByBind($bind = '1')
    {
        $connection = $this->_getConnection();

        return $salesData = $connection->fetchOne(
            $connection->select()
                ->from('artist_sales', 'SUM(qty_sold) as sold')
                ->where($bind)
        ) ?: 0;
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