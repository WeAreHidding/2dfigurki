<?php
/**
 * Essive
 */

class Figures_Artist_Block_Adminhtml_Workshop_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    protected $_isProductValid = true;

    public function __construct()
    {
        parent::__construct();
        $this->setId('artist_workshop_grid');
        $this->setDefaultSort('id');
        $this->setUseAjax(true);
    }

    /**
     * Retirve currently edited product model
     *
     * @return Mage_Catalog_Model_Product
     */
    protected function _getProduct()
    {
        return Mage::registry('current_product');
    }

    /**
     * Prepare collection
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('figures_artist/artist')->getCollection();
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
        return false; //$this->_getProduct()->getRelatedReadonly();
    }

    /**
     * Add columns to grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header'    => Mage::helper('catalog')->__('ID'),
            'sortable'  => true,
            'width'     => 60,
            'index'     => 'id'
        ));

        $this->addColumn('customer_id', array(
            'header'    => Mage::helper('catalog')->__('customer_id'),
            'sortable'  => true,
            'width'     => 60,
            'index'     => 'customer_id'
        ));

        $this->addColumn('artist_name', array(
            'header'    => Mage::helper('catalog')->__('artist_name'),
            'index'     => 'artist_name'
        ));

        $this->addColumn('char_name', array(
            'header'    => Mage::helper('catalog')->__('char_name'),
            'width'     => 100,
            'index'     => 'char_name'
        ));

        $this->addColumn('description', array(
            'header'    => Mage::helper('catalog')->__('description'),
            'width'     => 100,
            'index'     => 'description'
        ));

        $this->addColumn('image_path', array(
            'header'    => Mage::helper('catalog')->__('image_path'),
            'width'     => 100,
            'index'     => 'image_path'
        ));


        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/analogGrid', array('_current'=>true));
    }
}