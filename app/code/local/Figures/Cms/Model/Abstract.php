<?php
/**
 * Essive
 */
abstract class Figures_Cms_Model_Abstract extends Mage_Core_Model_Abstract
{
    /**
     * @var Varien_Db_Adapter_Pdo_Mysql
     */
    protected $_connection = null;

    protected function _construct()
    {
        $this->_connection = $this->_getConnection();
    }

    /**
     * @param $data
     * @return Varien_Data_Collection
     * @throws Exception
     */
    protected function _buildCollection($data)
    {
        $collection = new Varien_Data_Collection();

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