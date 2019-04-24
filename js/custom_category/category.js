var categoryId;
var activeFilters      = new Array();
var activeSort         = 'default';
var currentPage        = 1;
var pagesCount         = 1;

var disableLoadingFlag = false;

jQuery(document).ready(function() {
    categoryId = jQuery('#category').val();
    initFilters();
    loadProducts();
    initPager();

    //sort
    jQuery("#sort_by").on('change', function() {
        activeSort = jQuery("#sort_by option:selected").val();
        loadProducts();
    });

    //pager
    jQuery(document).on('click', ".page-link", function() {
        var suggestedPage = jQuery(this).text();
        if (suggestedPage == 'Previous') {
            suggestedPage = parseInt(currentPage) - 1;
        } else if (suggestedPage == 'Next') {
            suggestedPage = parseInt(currentPage) + 1;
        }
        changePage(suggestedPage);
    });
});

//display functions

function toggleBlock(block,slide) { "use strict";
    jQuery("#"+slide).slideToggle("slow");
    jQuery(block).find('i').toggleClass('icon-rotate');
}

function showLoader() {
    jQuery("#loader").show();
}
function hideLoader() {
    jQuery("#loader").hide();
}

//product loader
function loadProducts() {
    if (disableLoadingFlag) {
        return;
    }
    showLoader();
    jQuery.ajax({
        url: "/catalog/category/loadProducts",
        type: "GET",
        data: {category_id:categoryId, filters:Object.assign({}, activeFilters), sort:activeSort, page:currentPage},
        success: function (block) {
            jQuery(".category-products").empty();
            jQuery(".category-products").append(block);
        },
        cache: false
    });
    hideLoader();
}

//filters
function initFilters() {
    disableLoadingFlag = true;
    activeFilters['genre_id'] = new Array();
    activeFilters['artist_id'] = new Array();

    jQuery(".check-item").each(function() {
        jQuery(this).prop('checked', true);
        processFilter(this, jQuery(this).closest('ul').attr('id'));
    });
    disableLoadingFlag = false;
}

function processFilter(el, identifier) {
    var selector = jQuery(el);
    var type     = selector.attr('name');
    var id       = selector.data('filter');
    var parent   = selector.data('parent');
    var label    = selector.data('filter');
    var isNew    = selector.is(":checked");

    if (activeFilters[identifier].length == 0) {
        activeFilters[identifier] = new Array();
    }

    if (isNew) {
        activeFilters[identifier].push(id);
    } else {
        purgeElement(activeFilters[identifier], id);
    }

    loadProducts();
    if (!disableLoadingFlag) {
        initPager();
    }
}

function processPriceFilter() {
    var range = jQuery('#min_price').val() + '-' + jQuery('#max_price').val();
    activeFilters['price'] = range;
    addFilter('Price : ' + range + '$');

    loadProducts();
    initPager();
}

function purgePriceFilter(el) {
    activeFilters['price'] = "";
    closeFilter(el);
    loadProducts();
    initPager();
}

function closeFilter(a) {
    jQuery(a).remove();
}

function addFilter(name) {
    jQuery("#category-list").html("<a onclick=\"purgePriceFilter(this);\" class=\"btn btn-primary btn-sm\">"+name+" <i class=\"fa fa-remove\"></i></a>");
}

//pager
function initPager() {

    jQuery.ajax({
        url: "/catalog/category/getPagesCount",
        type: "GET",
        data: {category_id:categoryId, filters:Object.assign({}, activeFilters)},
        success: function (response) {
            pagesCount = response;
            processPagerHtml();
        },
        cache: false
    });
}

function changePage(number) {
    if (number == currentPage) {
        return;
    }
    currentPage = number;
    processPagerHtml();
    loadProducts();
}

function processPagerHtml() {
    var isFirst = false;
    var isLast  = false;
    if (currentPage == 1) {
        isFirst = true;
    }
    if (currentPage == pagesCount) {
        isLast  = true;
    }

    console.log(pagesCount);
    jQuery('#pager').empty();

    if (pagesCount <= 1) {
        return;
    }

    if (!isFirst) {
        jQuery('#pager').append('<li class="page-item"><span class="page-link" href="#">Previous</span></li>');
    }
    for (var i = 1; i <= pagesCount; i++) {
        if (i == currentPage) {
            jQuery('#pager').append('<li class="page-item"><span class="page-link current-page">' + i + '</span></li>');
        } else {
            jQuery('#pager').append('<li class="page-item"><span class="page-link">' + i + '</span></li>');
        }
    }
    if (!isLast) {
        jQuery('#pager').append('<li class="page-item"><span class="page-link">Next</span></li>');
    }
}

//add to cart
function addToCart(e, url) {
    showLoader();
    jQuery.ajax({
        url: url,
        type: "GET",
        success: function () {
            jQuery(e).empty();
            jQuery(e).append('<i class="fa fa-check"></i> <i class="fa fa-shopping-bag"></i>');
            hideLoader();
        },
        cache: false
    });
    hideLoader();
}

//F
function purgeElement(arr) {
    var what, a = arguments, L = a.length, ax;
    while (L > 1 && arr.length) {
        what = a[--L];
        while ((ax= arr.indexOf(what)) !== -1) {
            arr.splice(ax, 1);
        }
    }
    return arr;
}