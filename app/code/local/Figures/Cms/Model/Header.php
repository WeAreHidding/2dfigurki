<?php
/**
 * Essive
 */
class Figures_Cms_Model_Header extends Figures_Cms_Model_Abstract
{
    /**
     * @return object|Varien_Data_Collection
     * @throws Exception
     */
    public function getCollection()
    {
        $data = $this->_connection->fetchAll(
            $this->_connection->select()->from('custom_cms_header')
        );

        return $this->_buildCollection($data);
    }

    public function getItemById($id)
    {
        return $this->_connection->fetchRow(
            $this->_connection->select()
                ->from('custom_cms_header')
                ->where('id = ?', $id)
        );
    }

    /**
     * @param $data
     * @throws Zend_Db_Adapter_Exception
     */
    public function saveHeaderItem($data)
    {
        if (!$data['id']) {
            return;
        }
        if(isset($data['form_key'])) {
            unset($data['form_key']);
        }
        $this->_connection->update(
            'custom_cms_header',
            $data,
            'id=' . $data['id']
        );
    }

    /**
     * @param $data
     * @throws Zend_Db_Adapter_Exception
     */
    public function addHeaderItem($data)
    {
        if(isset($data['form_key'])) {
            unset($data['form_key']);
        }
        $this->_connection->insert(
            'custom_cms_header',
            $data
        );
    }

    /**
     * @return array
     */
    public function getFrontendItems()
    {
        return $this->_connection->fetchAll(
            $this->_connection->select()
                ->from('custom_cms_header', ['name', 'link'])
                ->where('is_enabled = 1')
                ->order('position')
        );
    }
}