<?php
class Figures_Catalog_Helper_CategoryFilters extends Mage_Core_Helper_Abstract
{
    protected $_definedFilters = [
        'genre_id' => [
            'front_id'   => 'genre',
            'front_name' => 'Genre',
            'type'       => 'check'
        ],
        'artist_id' => [
            'front_id'   => 'designer',
            'front_name' => 'Designer',
            'type'       => 'check'
        ]
    ];

    public function formatFiltersForFrontend($filters)
    {
        $genreFilterList  = array_unique($filters['genre_id']);
        $artistFilterList = array_unique($filters['artist_id']);

        $formattedFilters =  [
            'genre_id' => $this->_getGenreLabelsByIds($genreFilterList),
            'artist_id' => $this->_getArtistNamesByIds($artistFilterList),
        ];

        return $this->_convertFiltersToHtml($formattedFilters);
    }

    protected function _convertFiltersToHtml($formattedFilters)
    {
        $html = '';

        foreach ($formattedFilters as $identifier => $filter) {
            $frontIdentifier = $identifier; // to fix later
            $frontName       = $this->_definedFilters[$identifier]['front_name'];
            $type            = $this->_definedFilters[$identifier]['type'];
            $htmlType        = $type == 'check' ? 'checkbox' : $type;
            $html .= '<div class="category-sidebar__' . $frontIdentifier . '">';
            $html .= '<div class="sidebar-title"><p>' . $frontName . '</p>';
            $html .= '<a class="sidebar__department-arrow" onclick="toggleBlock(this,\'' . $frontIdentifier . '\')"><i class="fa fa-angle-up"></i></a></div>';
            $html .= '<ul id="' . $frontIdentifier . '" class="sidebar-list list-inline">';
            foreach ($filter as $id => $filterItem) {
                $html .= '<li><label class="container_' . $type . '">' . $filterItem;
                $html .= '<input data-parent="' . $frontName . '" data-label="' . $filterItem . '" data-filter="' . $id . '" name="' . $type .'" type="' . $htmlType . '" 
                    class="' . $type . '-item" onclick="processFilter(this, \'' . $frontIdentifier . '\')">';
                $html .= '<span class="' . $type . 'mark"></span>';
                $html .= '</label></li>';
            }
            $html .= '</div></ul>';
        }

        return $html;
    }

    protected function _getGenreLabelsByIds($genreIds)
    {
        $connection = $this->_getConnection();

        return $connection->fetchPairs(
            $connection->select()
                ->from('artist_genre')
                ->where('id IN(?)', $genreIds)
        );
    }

    protected function _getArtistNamesByIds($artistIds)
    {
        $connection = $this->_getConnection();
        $nicknameAttributeId = $connection->fetchOne(
            $connection->select()
                ->from('eav_attribute', 'attribute_id')
                ->where('attribute_code = ?', 'artist_nickname')
        );

        return $connection->fetchPairs(
            $connection->select()
                ->from('customer_entity_varchar', ['entity_id', 'value'])
                ->where('attribute_id = ?', $nicknameAttributeId)
                ->where('entity_id IN(?)', $artistIds)
        );
    }

    /**
     * @return Varien_Db_Adapter_Pdo_Mysql
     */
    protected function _getConnection()
    {
        return Mage::getModel('core/resource')->getConnection('core_read');
    }
}