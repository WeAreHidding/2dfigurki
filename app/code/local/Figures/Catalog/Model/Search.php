<?php
/**
 * Essive
 */
class Figures_Catalog_Model_Search extends Mage_Core_Model_Abstract
{
    const DEFAULT_ARTIST_LIMIT = 1;
    const DEFAULT_CATEGORY_LIMIT = 1;
    const DEFAULT_PRODUCT_LIMIT = 10;

    protected $_searchString;

    protected $_config;

    public function getSearchResults($searchString)
    {
        $this->_searchString = $searchString;
        $this->_config = $this->_getConfig();

        return $this->_getCategoriesData();
//        $designers;
    }

    protected function _getDesignersData()
    {
        $connection = $this->_getConnection();
        $result = [];

        $nicknameAttributeId = $connection->fetchOne(
            $connection->select()
                ->from('eav_attribute', 'attribute_id')
                ->where('attribute_code = ?', 'artist_nickname')
        );

        //TODO: add attributes after customer implementation
        $customersInfo = $connection->fetchAll(
            $connection->select()
                ->from('customer_entity_varchar', ['entity_id', 'value'])
                ->where('attribute_id = ?', $nicknameAttributeId)
                ->where('value LIKE ?', '%' . $this->_searchString . '%')
                ->limit($this->_config['designers_limit'])
        );

        foreach ($customersInfo as $customerInfo) {
            $result[] = [
                'name' => $customerInfo['value'],
                'link' => '#'
            ];
        }

        return $result;
    }

    protected function _getCategoriesData()
    {
        $result = [];
        $categories = Mage::getModel('catalog/category')
            ->getCollection()
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('name', array('like' => '%' . $this->_searchString. '%'))
//            ->addFieldToFilter('category_custom_type', $customType)
            ->addIsActiveFilter();

        foreach ($categories as $category) {
            $result[] = [
                'name' => $category->getName(),
                'link' => $category->getUrl()
            ];
        }

        return $result;
    }

    protected function _getConfig()
    {
        $config = Mage::getStoreConfig('custom_search/general');

        $config['designers_limit'] = $config['designers_limit'] ? $config['designers_limit'] : static::DEFAULT_ARTIST_LIMIT;
        $config['categories_limit'] = $config['categories_limit'] ? $config['categories_limit'] : static::DEFAULT_CATEGORY_LIMIT;
        $config['products_limit'] = $config['products_limit'] ? $config['products_limit'] : static::DEFAULT_PRODUCT_LIMIT;

        return $config;
    }

    /**
     * @return Varien_Db_Adapter_Pdo_Mysql
     */
    protected function _getConnection()
    {
        return Mage::getModel('core/resource')->getConnection('core_read');
    }
}