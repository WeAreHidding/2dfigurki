var categoryId;
var activeFilters = new Array();

jQuery(document).ready(function() {
    categoryId = jQuery('#category').val();
    loadProducts();
    initFilters();
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

function closeCategory(a) {
    jQuery(a).html("");
}

function addCategory(name) {
    jQuery("#category-list").html("<a onclick=\"closeCategory(this);\" class=\"btn btn-primary btn-sm\">"+name+" <i class=\"fa fa-remove\"></i></a>");
}

//product loader
function loadProducts() {
    jQuery.ajax({
        url: "/catalog/category/loadProducts",
        type: "GET",
        data: {category_id:categoryId, filters:activeFilters},
        success: function (block) {
            jQuery(".category-products").empty();
            jQuery(".category-products").append(block);
        },
        cache: false
    });
}

//filters
function initFilters() {
    activeFilters['genre_id'] = new Array();
    activeFilters['artist_id'] = new Array();
}

function processFilter(el, identifier) {
    var selector = jQuery(el);
    var type     = selector.attr('name');
    var id       = selector.data('filter');
    var isNew    = selector.is(":checked");
    var toRemove = !selector.is(":checked");


    if (activeFilters[identifier].length == 0) {
        alert(0);
        activeFilters[identifier] = new Array();
        activeFilters[identifier][0] = id;
        activeFilters[identifier][1] = 322;
    } else {
        alert(1);
        activeFilters[identifier].forEach(function(item, i, arr) {
            console.log(item);
        });
    }


    // console.log(activeFil ters);
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