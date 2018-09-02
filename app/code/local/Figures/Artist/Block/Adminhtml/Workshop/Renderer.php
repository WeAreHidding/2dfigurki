<?php
/**
 * Essive
 */

class Figures_Artist_Block_Adminhtml_Workshop_Renderer extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
//        $val = Mage::helper('catalog/image')->init($row, 'thumbnail')->resize(97);
        $out = "<img src=" . Mage::getBaseUrl('media') . DS . 'workshop/user_images/' . $row['customer_id'] . $row['image_path'] ." width='97px'/>";
        return $out;
    }
}