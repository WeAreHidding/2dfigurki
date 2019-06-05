<?php

class Figures_Catalog_Model_Source_SearchEntities extends Mage_Core_Model_Abstract
{
    protected $_entities = [
        'designer' => 'Designer',
        'category' => 'Category',
        'product' => 'Product'
    ];

    public function getAllOptions()
    {
        if (!$this->_options) {
            foreach ($this->_entities as $id => $code) {
                $options[] = array(
                    'value' => $id,
                    'label' => $code
                );
            }
            $this->_options = $options;
        }
        return $this->_options;
    }

    public function toOptionArray()
    {
        return $this->getAllOptions();
    }
}