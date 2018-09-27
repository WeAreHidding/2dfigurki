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

    public function getFormCategories()
    {
        return $this->getCategoryByFilter('FORM');
    }

    public function getCategoryByFilter($customType)
    {
        $categoryData = [];
        $categories = Mage::getModel('catalog/category')
            ->getCollection()
            ->addAttributeToSelect('*')
            ->addFieldToFilter('category_custom_type', $customType)
            ->addIsActiveFilter();

        foreach ($categories as $category) {
            $categoryData[] = [
                'name' => $category->getName(),
                'id'   => $category->getId()
            ];
        }

        return $categoryData;
    }

    public function getEditableData()
    {
        return $this->_rowData;
    }

    protected function _initRowData()
    {
        $this->_rowData = $this->_getArtistModel()->getWorkDataById($this->_rowId);
    }


    /**
     * @return Figures_Artist_Model_Artist
     */
    protected function _getArtistModel()
    {
        return Mage::getModel('figures_artist/artist');
    }
}