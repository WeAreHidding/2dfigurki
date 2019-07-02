<?php

class Figures_Catalog_Block_Product_List extends Mage_Catalog_Block_Product_Abstract
{
    protected $_definedFilters = [
        'genre_id',
        'artist_id'
    ];

    public function getAvailableFilters()
    {
        $category = Mage::getModel('catalog/category')->load($this->getCategoryId());
        $filters = [];
        $productCollection = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToSelect('*')
            ->addCategoryFilter($category)
            ->load();

        foreach ($productCollection as $product) {
            foreach ($this->_definedFilters as $definedFilter) {
                $filters[$definedFilter][] = $product->getData($definedFilter);
            }
        }

        if (!$filters) {
            return false;
        }

        $filters = $this->_getCategoryFiltersHelper()->formatFiltersForFrontend($filters);

        return $filters;
    }

    /**
     * @return Figures_Catalog_Helper_CategoryFilters
     */
    protected function _getCategoryFiltersHelper()
    {
        return Mage::helper('figures_catalog/categoryFilters');
    }


    public function getFiltersHtml(){

        $current_category_id = Mage::registry('current_category')->getId();
        $parent_category_id=Mage::getSingleton('catalog/category')->load($current_category_id)->getParentId();

        $category_filters = [3, 4, 5];

        $html = '';

        if (($key = array_search($current_category_id, $category_filters)) !== false) {
            unset($category_filters[$key]);
        }

        if (($key = array_search($parent_category_id, $category_filters)) !== false) {
            unset($category_filters[$key]);
        }

        foreach ($category_filters as $parent_category){

            $children_categories_data=[];

            $_category = Mage::getSingleton('catalog/category')->load($parent_category);
            $parent_category_data = $_category->getData();
            $children_categories = Mage::getModel('catalog/category')->getCategories($parent_category);

            $html .= '
            <div class="category-sidebar__'.$parent_category_data['url_key'].'">
                <div class="sidebar-title">
                    <p>'.$parent_category_data['name'].'</p>
                    <a class="sidebar__department-arrow" onclick="toggleBlock(this,\''.$parent_category_data['url_key'].'\')">
                        <i class="fa fa-angle-up"></i></a>
                </div>
                <ul id="'.$parent_category_data['url_key'].'" class="sidebar-list list-inline">
                ';

            foreach ($children_categories as $category) {
                $children_categories_data[] = $category->getData();
            }

            foreach ($children_categories_data as $children_category){
                $html.='
                     <li>
                        <label class="container_check">'.$children_category['name'].'
                            <input data-parent="Genre" data-label="Movies" data-filter="1" name="check" type="checkbox" class="check-item" onclick="processFilter(this, \'genre_id\')">
                            <span class="checkmark"></span>
                        </label>
                    </li>
                    ';
            }

            $html .= '
              </ul>
            </div>
            ';
        }

        return $html;

    }


}
