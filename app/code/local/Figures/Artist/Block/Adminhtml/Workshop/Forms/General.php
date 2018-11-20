<?php
/**
 * Essive
 */

class Figures_Artist_Block_Adminhtml_Workshop_Forms_General extends Figures_Artist_Block_Adminhtml_Workshop_Forms_Abstract
{
    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();
        $this->setHtmlId('analog_parent_form');
        $this->setTemplate('artist/general_form.phtml');

        $this->_rowId = $this->getRequest()->getParam('id');
        $this->_initRowData();
    }

    /**
     * @return array|bool
     */
    public function getProposedFormCategories()
    {
        $formCategories = $this->getFormCategories();
        $proposedCategories = $this->getEditableData()['proposed_form_category'];
        $proposedCategories = explode(',', $proposedCategories);
        $result = [];
        foreach ($formCategories as $formCategory) {
            $formCategory['is_used'] = in_array($formCategory['id'], $proposedCategories) ? 1 : 0;
            $result[] = $formCategory;
        }

        return $result;
    }
}