
jQuery(document).ready(function() {
    jQuery("#sidebar").mCustomScrollbar({
        theme: "minimal"
    });
    loadContent("dash");

    jQuery('#sidebarCollapse').on('click', function () {
        jQuery('#sidebar, #content').toggleClass('active');
        jQuery('.collapse.in').toggleClass('in');
        jQuery('a[aria-expanded=true]').attr('aria-expanded', 'false');
    });
});

function menuToggle(a) {
    jQuery(".components li").each(function() {
        jQuery(this).removeClass('active');
    });
    jQuery(a).toggleClass('active');
    var id=jQuery(a).attr('id');

    switch(id) {
        case 'menu-design': loadContent("design"); break;
        case 'menu-design-management': loadContent("design_management"); break;
        case 'menu-dash': loadContent("dash"); break;
        case 'menu-stats': loadContent("stats"); break;
        default: console.log('error'); break;
    }
}

function loadContent(content) {
    jQuery('#content_dash').remove();
    jQuery('#content_design').remove();
    jQuery('#content_design_management').remove();
    jQuery('#content_stats').remove();

    jQuery.ajax({
        url: "/figures_dashboard/dashboard/loadTab",
        type: "GET",
        data: {tab: content},
        success: function (block) {
            jQuery("#dashboardMainContainer").append(block);
        },
        cache: false
    });
}

//Design functionality

function validateDesign() {
    var isValid     = true;
    var name        = jQuery('#char_name');
    var mainTag     = jQuery('#main_tag');
    var description = jQuery('#description');
    var tags        = jQuery('#tags');
    var formCats    = jQuery('.check-form');
    console.log(formCats);

    if (!name.val()) {
        isValid = false;
        name.addClass('not-valid-field');
    }
    if (!mainTag.val()) {
        isValid = false;
        mainTag.addClass('not-valid-field');
    }
    if (!description.val()) {
        isValid = false;
        description.addClass('not-valid-field');
    }
    if (!tags.val()) {
        isValid = false;
        tags.addClass('not-valid-field');
    }

    return isValid;
}

function saveDesign(formData) {
    jQuery.ajax({
        url: "/figures_dashboard/dashboard/saveInfo",
        type: "POST",
        data: formData,
        success: function (msg) {
        },
        cache: false,
        contentType: false,
        processData: false
    });
}

//Statistics

//Sales tab
function getSalesData(customerId) {
    var filter  = jQuery('#salesFilter');
    var from    = jQuery('#salesDateFrom');
    var to      = jQuery('#salesDateTo');
    console.log(to.val());
    jQuery.ajax({
        url: "/figures_dashboard/dashboard/getSalesData",
        type: "POST",
        data: {status: filter.val(), from: from.val(), to: to.val(), customer_id: customerId},
        success: function (msg) {
            console.log(msg)
        },
        cache: false
    });
}
