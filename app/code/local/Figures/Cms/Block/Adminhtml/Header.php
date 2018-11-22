<?php
/**
 * Essive
 */

class Figures_Cms_Block_Adminhtml_Header extends Mage_Adminhtml_Block_Widget
{
    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->setHtmlId('custom_cms_header');
        $this->setTemplate('custom_cms/header.phtml');
    }

    public function getHeaderItems()
    {
        $connection = $this->_getConnection();
        $items = $connection->fetchAll(
            $connection->select()
                ->from('custom_cms_header')
        );

        return $items;
    }

    /**
     * @return Varien_Db_Adapter_Pdo_Mysql
     */
    protected function _getConnection()
    {
        return Mage::getModel('core/resource')->getConnection('core_write');
    }

}