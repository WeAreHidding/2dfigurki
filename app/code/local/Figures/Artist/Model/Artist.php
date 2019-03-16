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
     * @param $workIds
     * @throws Zend_Db_Adapter_Exception
     */
    public function deleteDesigns($workIds)
    {
        $workIds = implode(',', $workIds);

        $query = "DELETE FROM artist_work WHERE id IN({$workIds})";
        $this->_getConnection()->query($query);
    }

    /**
     * @param $customerId
     * @return mixed
     */
    public function getDesignsByCustomer($customerId)
    {
        $connection = $this->_getConnection();

        $workData = $connection->fetchAll(
            $connection->select()
                ->from('artist_work')
                ->where('customer_id = ?', $customerId)
        );
        if ($workData) {
            foreach ($workData as $key => $workItem) {
                $workData[$key]['image_path'] = Mage::getBaseUrl('media') . 'workshop/user_images/' .
                    $workItem['customer_id'] . $workItem['image_path'];//SUM(artist_comission_net) as total_sum
                $workData[$key]['total_count'] = $connection->fetchOne("SELECT SUM(qty_sold) FROM artist_sales WHERE  work_id = {$workItem['id']}");
                $workData[$key]['total_sum_not_paid'] = $connection->fetchOne("SELECT SUM(artist_comission_net) FROM artist_sales WHERE  work_id = {$workItem['id']}  AND artist_comission_status = 'not_paid'");
                $workData[$key]['total_sum_paid'] = $connection->fetchOne("SELECT SUM(artist_comission_net) FROM artist_sales WHERE  work_id = {$workItem['id']} AND artist_comission_status = 'paid'");
                $workData[$key]['product_data'] =
                    $connection->fetchCol(
                        $connection->select()
                            ->from('artist_product', 'product_id')
                            ->where('artist_id = ?', $workItem['customer_id'])
                            ->where('work_id = ?', $workItem['id'])
                    );
                foreach ($workData[$key]['product_data'] as $pk => $productId) {
                    unset($workData[$key]['product_data'][$pk]);
                    $product = Mage::getModel('catalog/product')->load($productId);
                    $workData[$key]['product_data'][$pk]['image_path'] = Mage::getModel('catalog/product_media_config')->getMediaUrl($product->getImage());
                    $workData[$key]['product_data'][$pk]['link'] = Mage::getBaseUrl() . $product->getUrlPath();
                }
            }

            return $workData;
        }

        return false;
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

    public function getArtistProductByProductId($productId)
    {
        $connection = $this->_getConnection();

        return $connection->fetchRow(
            $connection->select()
                ->from('artist_product', ['artist_id', 'work_id'])
                ->where('product_id = ?', $productId)
        );
    }

    /**
     * @param $artistId
     * @param $productId
     * @param $workId
     * @param $mainTag
     * @param $formCategoryId
     * @throws Zend_Db_Adapter_Exception
     */
    public function saveArtistProduct($artistId, $productId, $workId, $mainTag, $formCategoryId)
    {
        $connection = $this->_getConnection();

        $connection->insert('artist_product', [
            'artist_id'  => $artistId,
            'product_id' => $productId,
            'work_id'    => $workId,
            'main_tag'   => $mainTag,
            'parent_form_category' => $formCategoryId
        ]);
    }

    /**
     * @param $productId
     * @param $qty
     * @param $price
     * @throws Zend_Db_Adapter_Exception
     */
    public function increaseProductValues($productId, $qty, $price)
    {
        $connection = $this->_getConnection();
        $data = $connection->fetchRow(
            $connection->select()->from('artist_product', ['total_sold', 'total_income'])->where('product_id = ?', $productId)
        );
        $connection->update('artist_product',
            [
                'total_sold'   => $data['total_sold'] + $qty,
                'total_income' => $data['total_income'] + $price
            ],
            'product_id=' . $productId);
    }

    public function toEng($string)
    {
        $converter = array(
            'а' => 'a',   'б' => 'b',   'в' => 'v',
            'г' => 'g',   'д' => 'd',   'е' => 'e',
            'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
            'и' => 'i',   'й' => 'y',   'к' => 'k',
            'л' => 'l',   'м' => 'm',   'н' => 'n',
            'о' => 'o',   'п' => 'p',   'р' => 'r',
            'с' => 's',   'т' => 't',   'у' => 'u',
            'ф' => 'f',   'х' => 'h',   'ц' => 'c',
            'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
            'ь' => '',    'ы' => 'y',   'ъ' => '',
            'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

            'А' => 'A',   'Б' => 'B',   'В' => 'V',
            'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
            'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
            'И' => 'I',   'Й' => 'Y',   'К' => 'K',
            'Л' => 'L',   'М' => 'M',   'Н' => 'N',
            'О' => 'O',   'П' => 'P',   'Р' => 'R',
            'С' => 'S',   'Т' => 'T',   'У' => 'U',
            'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
            'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
            'Ь' => '',    'Ы' => 'Y',   'Ъ' => '',
            'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
        );
        return strtr($string, $converter);
    }

    public function getDesignImagePrefix()
    {
        return Mage::getBaseDir('media') . '/workshop/user_images/';
    }

    /**
     * @return Varien_Db_Adapter_Pdo_Mysql
     */
    protected function _getConnection()
    {
        return Mage::getModel('core/resource')->getConnection('core_write');
    }
}