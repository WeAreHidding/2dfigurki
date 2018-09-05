<?php
class Figures_Artist_Model_Source_CategoryType extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    /**
     * Retrieve all options array
     *
     * @return array
     */
    public function getAllOptions()
    {
        if (is_null($this->_options)) {
            $this->_options = array(
                array(
                    'label' => '',
                    'value' => ''
                ),
                array(
                    'label' => 'Physical form',
                    'value' => 'FORM'
                ),
                array(
                    'label' => 'Genre',
                    'value' => 'GENRE'
                ),
                array(
                    'label' => 'Genre item',
                    'value' => 'GENRE_ITEM'
                ),
            );
        }
        return $this->_options;
    }
}