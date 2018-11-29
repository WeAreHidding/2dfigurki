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
     * @return $this
     * @throws Exception
     */
    protected function _prepareColumns()
    {
        $this->addColumn('id', array(
            'header'    => Mage::helper('catalog')->__('Design ID'),
            'sortable'  => true,
            'width'     => 60,
            'index'     => 'id'
        ));

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

        $this->addColumn('created_products_qty', array(
            'header'    => Mage::helper('catalog')->__('Created<br>Product<br>Qty'),
            'width'     => 50,
            'index'     => 'created_products_qty'
        ));

        $this->addColumn('status', array(
            'header'    => Mage::helper('catalog')->__('Status'),
            'width'     => 100,
            'index'     => 'status'
        ));


        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('id');
        $this->getMassactionBlock()->setFormFieldName('id');

        $this->getMassactionBlock()->addItem('export', array(
            'label'=> Mage::helper('tax')->__('Export'),
            'url'  => $this->getUrl('*/artist_massactions/massExport', array('' => '')),
            'confirm' => Mage::helper('tax')->__('Start now?')
        ));


        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> Mage::helper('tax')->__('Delete'),
            'url'  => $this->getUrl('*/artist_massactions/massDelete', array('' => '')),
            'confirm' => Mage::helper('tax')->__('Are you sure? You will lost all products linked to this design')
        ));

        return $this;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/editGeneral', array('id' => $row->getId()));
    }
}