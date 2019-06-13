jQuery(document).ready(function() {
    RefreshBasketItems();
});

function RefreshBasketItems() {
    jQuery.ajax({
        url: "/catalog/category/getCartItemsCount",
        type: "GET",
        success: function (data) {
            jQuery(".basket-product-amount").html('<span>'+data+'</span>');
        },
        cache: false
    });
}