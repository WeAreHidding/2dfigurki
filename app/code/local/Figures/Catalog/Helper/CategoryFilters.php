<?php
class Figures_Catalog_Helper_CategoryFilters extends Mage_Core_Helper_Abstract
{
    protected $_definedFilters = [
        'form' => [
            'front_id'    => CustomEntities::FORM_URL,
            'front_name'  => CustomEntities::FORM_NAME,
            'type'        => 'check',
            'category_id' => CustomEntities::FORM_ID
        ],
        'genre' => [
            'front_id'    => CustomEntities::GENRE_URL,
            'front_name'  => CustomEntities::GENRE_NAME,
            'type'        => 'check',
            'category_id' => CustomEntities::GENRE_ID
        ],
        'fandom' => [
            'front_id'    => CustomEntities::FANDOM_URL,
            'front_name'  => CustomEntities::FANDOM_NAME,
            'type'        => 'check',
            'category_id' => CustomEntities::FANDOM_ID
        ],
        'artist_id' => [
            'front_id'   => 'designer',
            'front_name' => 'Designer',
            'type'       => 'check'
        ]
    ];

    public function getAvailableFilters($productCollection)
    {
        $filters = $artistIds = [];
        foreach ($productCollection as $product) {
            foreach ($this->_definedFilters as $identifier => $definedFilter) {
                if (isset($definedFilter['category_id'])) {
                    $filters[$identifier] = CustomEntities::helper()->getCategoriesForProduct($definedFilter['category_id'], $product);
                }
                //to do general filter by attribute later
            }
            $artistIds[] = $product->getArtistId();
        }

        $filters['artist_id'] = $this->_getArtistNamesByIds($artistIds);

        return $filters ? $this->_convertFiltersToHtml($filters) : false;
    }

    public function filterCollection($productCollection, $filters, $sort = '', $page = '')
    {
        foreach ($filters as $filterId => $filter) {
            if (!$filter) {
                continue;
            }
            if ($filterId == 'price') {
                $filter = explode('-', $filter);
                $productCollection->addFieldToFilter('price', ['from'=> $filter[0],'to'=> $filter[1]]);
                continue;
            }

            $productCollection->addAttributeToFilter($filterId, ['in' => $filter]);
        }

        if ($sort && ($sort != 'default')) {
            $sort = explode('_', $sort);
            $productCollection->addAttributeToSort($sort[0], $sort[1]);
        }

        if ($page) {
            $productCollection->setCurPage($page)
                ->setPageSize(static::PRODUCTS_PER_PAGE);
        }

        return $productCollection;
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