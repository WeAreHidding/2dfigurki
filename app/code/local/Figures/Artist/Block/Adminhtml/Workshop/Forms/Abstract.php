<?php
/**
 * Essive
 */

class Figures_Artist_Block_Adminhtml_Workshop_Forms_Abstract extends Mage_Adminhtml_Block_Widget
{
    protected $_rowId = null;

    protected $_rowData = [];

    /**
     * @return array
     */
    public function getCustomerInfo()
    {
        $customerId = $this->_rowData['customer_id'];
        $customer = Mage::getModel('customer/customer')->load($customerId);
        $filepath = Mage::getBaseDir('media') . DS . 'workshop/user_images/' . $this->_rowData['customer_id'] . $this->_rowData['image_path'];
        $filepath = str_replace("\\", "\\\\\\", $filepath);

        return [
            'customer_id' => $customerId,
            'nickname'    => $customer->getData('artist_nickname'),
            'bo_link'     => Mage::helper("adminhtml")->getUrl("adminhtml/customer/edit/", array("id" => $customerId)),
            'image'       => Mage::getBaseUrl('media') . DS . 'workshop/user_images/' . $this->_rowData['customer_id'] . $this->_rowData['image_path'],
            'filepath'    => $filepath
        ];
    }

    public function getEditableData()
    {
        return $this->_rowData;
    }

    protected function _initRowData()
    {
        $connection = $this->_getConnection();

        $this->_rowData = $connection->fetchRow(
            $connection->select()->from('artist_work')->where('id = ?', $this->_rowId)
        );
    }


    /**
     * @return Varien_Db_Adapter_Pdo_Mysql
     */
    protected function _getConnection()
    {
        return Mage::getModel('core/resource')->getConnection('core_write');
    }
}