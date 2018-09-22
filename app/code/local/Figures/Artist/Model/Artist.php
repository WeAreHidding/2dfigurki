<?php
/**
 * Essive
 */
class Figures_Artist_Model_Artist extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {
        $this->_init('figures_artist/artist');
    }

    /**
     * @param $workId
     * @return mixed
     */
    public function getWorkDataById($workId)
    {
        $connection = $this->_getConnection();

        return $connection->fetchRow(
            $connection->select()
                ->from('artist_work')
                ->where('id = ?', $workId)
        );
    }

    /**
     * @param $workId
     * @return array
     */
    public function getProductDataByWorkId($workId)
    {
        $connection = $this->_getConnection();

        return $connection->fetchAll(
            $connection->select()
                ->from('artist_product')
                ->where('work_id = ?', $workId)
        );
    }

    /**
     * @param $productId
     * @return string
     */
    public function getSummaryOrderedForProduct($productId)
    {
        $connection = $this->_getConnection();

        return $connection->fetchOne(
            "SELECT SUM(qty_ordered) FROM sales_flat_order_item WHERE product_id = {$productId}"
        );
    }

    public function saveArtistProduct($artistId, $productId, $workId)
    {
        $connection = $this->_getConnection();

        $connection->insert('artist_product', [
            'artist_id'  => $artistId,
            'product_id' => $productId,
            'work_id'    => $workId
        ]);
    }


    /**
     * @return Varien_Db_Adapter_Pdo_Mysql
     */
    protected function _getConnection()
    {
        return Mage::getModel('core/resource')->getConnection('core_write');
    }
}