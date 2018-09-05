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
        $this->addColumn('customer_id', array(
            'header'    => Mage::helper('catalog')->__('Customer ID'),
            'sortable'  => true,
            'width'     => 60,
            'index'     => 'customer_id'
        ));

        $this->addColumn('Nickname', array(
            'header'    => Mage::helper('catalog')->__('Nickname'),
            'width'     => 100,
            'index'     => 'artist_name'
        ));

        $this->addColumn('char_name', array(
            'header'    => Mage::helper('catalog')->__('Char Name'),
            'width'     => 100,
            'index'     => 'char_name'
        ));

        $this->addColumn('tags', array(
            'header'    => Mage::helper('catalog')->__('Tags'),
            'width'     => 200,
            'index'     => 'tags'
        ));

        $this->addColumn('description', array(
            'header'    => Mage::helper('catalog')->__('Description'),
            'index'     => 'description'
        ));

        $this->addColumn('image_path', array(
            'header'    => Mage::helper('catalog')->__('Image'),
            'width'     => 100,
            'index'     => 'image_path',
            'renderer'  => 'Figures_Artist_Block_Adminhtml_Workshop_Renderer',
            'sortable'  => false,
            'filter'    => false
        ));

        $this->addColumn('status', array(
            'header'    => Mage::helper('catalog')->__('Status'),
            'width'     => 100,
            'index'     => 'status'
        ));


        return parent::_prepareColumns();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }
}