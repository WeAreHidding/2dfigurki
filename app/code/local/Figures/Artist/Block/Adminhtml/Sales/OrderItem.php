<?php
/**
 * Essive
 */

class Figures_Artist_Block_Adminhtml_Sales_OrderItem extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('order_item_grid');
        $this->setUseAjax(true);
    }

    /**
     * Prepare collection
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = $this->_getSalesModel()->getCollection();
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * Checks when this block is readonly
     *
     * @return boolean
     */
    public function isReadonly()
    {
        return true;
    }

    /**
     * @return $this
     * @throws Exception
     */
    protected function _prepareColumns()
    {
        $this->addColumn('work_id', array(
            'header'    => Mage::helper('catalog')->__('Design ID'),
            'sortable'  => true,
            'width'     => 60,
            'index'     => 'work_id'
        ));

        $this->addColumn('artist_id', array(
            'header'    => Mage::helper('catalog')->__('Artist ID'),
            'sortable'  => true,
            'width'     => 60,
            'index'     => 'artist_id'
        ));

        $this->addColumn('product_id', array(
            'header'    => Mage::helper('catalog')->__('Product ID'),
            'width'     => 60,
            'index'     => 'product_id'
        ));

        $this->addColumn('qty_sold', array(
            'header'    => Mage::helper('catalog')->__('Sold'),
            'width'     => 100,
            'index'     => 'qty_sold'
        ));

        $this->addColumn('price', array(
            'header'    => Mage::helper('catalog')->__('Price'),
            'width'     => 100,
            'index'     => 'price'
        ));

        $this->addColumn('discount', array(
            'header'    => Mage::helper('catalog')->__('Discount'),
            'width'     => 100,
            'index'     => 'discount'
        ));

        $this->addColumn('artist_comission', array(
            'header'    => Mage::helper('catalog')->__('Artist Comission'),
            'width'     => 100,
            'index'     => 'artist_comission'
        ));

        $this->addColumn('artist_comission_status', array(
            'header'    => Mage::helper('catalog')->__('Status'),
            'width'     => 100,
            'index'     => 'artist_comission_status'
        ));


        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    public function getRowUrl($row)
    {
        return '#';
    }

    /**
     * @return Figures_Artist_Model_Sales
     */
    protected function _getSalesModel()
    {
        return Mage::getModel('figures_artist/sales');
    }
}