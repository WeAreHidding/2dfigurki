<?php
/**
 * Essive
 */

class Figures_Cms_Block_Adminhtml_Header extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('cms_header_grid');
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
        $collection = Mage::getModel('figures_cms/header')->getCollection();
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
            'header'    => Mage::helper('catalog')->__('Item ID'),
            'sortable'  => true,
            'width'     => 60,
            'index'     => 'id'
        ));

        $this->addColumn('name', array(
            'header'    => Mage::helper('catalog')->__('Name'),
            'sortable'  => true,
            'width'     => 200,
            'index'     => 'name'
        ));

        $this->addColumn('link', array(
            'header'    => Mage::helper('catalog')->__('Link'),
            'width'     => 1000,
            'index'     => 'link'
        ));

        $this->addColumn('position', array(
            'header'    => Mage::helper('catalog')->__('Position'),
            'width'     => 100,
            'index'     => 'position'
        ));

        $this->addColumn('is_enabled', array(
            'header'    => Mage::helper('catalog')->__('Enabled'),
            'width'     => 100,
            'index'     => 'is_enabled',
            'type'      =>'options',
            'options'   => array('1' => 'Yes', '0' => 'No')
        ));

        return parent::_prepareColumns();
    }

    public function getMainButtonsHtml()
    {
        $html = parent::getMainButtonsHtml();
        $newButton = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
                'label' => Mage::helper('adminhtml')->__('New'),
                'onclick' => 'setLocation(\'' . $this->getUrl('*/*/new') . '\')',
                'class' => ''
            ));
        $html .= $newButton->toHtml();

        return $html;
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current' => true));
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}