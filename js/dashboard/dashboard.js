
jQuery(document).ready(function() {
    jQuery("#sidebar").mCustomScrollbar({
        theme: "minimal"
    });
    loadContent("dash");
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
        case 'menu-account': loadContent("account"); break;
        default: console.log('error'); break;
    }
}

function loadContent(content, check) {
    // if (check) {
    //     var hash = window.location.hash.substr(1);
    // }
    jQuery('#content_dash').remove();
    jQuery('#content_design').remove();
    jQuery('#content_design_management').remove();
    jQuery('#content_stats').remove();
    jQuery('#content_account').remove();

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
    var message = '';

    var isValid     = true;
    var name        = jQuery('#char_name');
    var mainTag     = jQuery('#main_tag');
    var description = jQuery('#description');
    var tags        = jQuery('#tags');
    var forms       = jQuery('#formsGroup');
    var terms       = jQuery('#termsLabel');
    var copyright   = jQuery('#copyrightLabel');

    if (!jQuery('#downForm').is(":visible")) {
        isValid = false;
        message = 'Please, upload image. ';
    }

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

    if (jQuery('.check-form:checked').length === 0) {
        isValid = false;
        forms.addClass('not-valid-field');
    }

    if (!jQuery('#terms').is(":checked")) {
        isValid = false;
        terms.addClass('not-valid-text');
    }

    if (!jQuery('#copyright').is(":checked")) {
        isValid = false;
        copyright.addClass('not-valid-text');
    }

    if (!isValid) {
        message += 'Please, fill all mandatory fields.';
        showNotify('No needed data', message, 'danger');
    }

    return isValid;
}

function saveDesign(formData) {
    jQuery.ajax({
        url: "/figures_dashboard/dashboard/saveInfo",
        type: "POST",
        data: formData,
        success: function () {
            showNotify('Your design is saved! ', 'You will get response in few days', 'success');
            loadContent('design');
        },
        cache: false,
        contentType: false,
        processData: false
    });
}

function removeValidation(element) {
    element.removeClassName('not-valid-text');
    element.removeClassName('not-valid-field');
}

function imageIsLoaded(e) {
    jQuery('#downImg').attr('src', e.target.result);
    jQuery('#downForm').show();
    jQuery('#downFigure').hide();
}

function closeImageLoaded() {
    jQuery('#downForm').hide();
    jQuery('#downFigure').show();
    jQuery(":file").val('');
}

//Statistics

//Credit tab
function getCreditData(customerId) {
    var filter  = jQuery('#creditFilter');
    var from    = jQuery('#creditDateFrom');
    var to      = jQuery('#creditDateTo');

    jQuery.ajax({
        url: "/figures_dashboard/dashboard/getCreditData",
        type: "POST",
        data: {status: filter.val(), from: from.val(), to: to.val(), customer_id: customerId},
        success: function (block) {
            jQuery("#creditList").empty();
            jQuery("#creditList").append(block);
        },
        cache: false
    });
}

//Sales tab
function getSalesData(customerId) {
    var filter  = jQuery('#salesFilter');
    var from    = jQuery('#salesDateFrom');
    var to      = jQuery('#salesDateTo');

    jQuery.ajax({
        url: "/figures_dashboard/dashboard/getSalesData",
        type: "POST",
        data: {status: filter.val(), from: from.val(), to: to.val(), customer_id: customerId},
        success: function (block) {
            jQuery("#salesList").empty();
            jQuery("#salesList").append(block);
        },
        cache: false
    });
}

function showNotify(title,text,type,delay) {
    delay = typeof delay !== 'undefined' ? delay : 5000;
    title="<strong>"+title+":</strong>";

    jQuery.notify({
        icon: 'glyphicon glyphicon-star',
        title: title,
        message: text
    },{
        delay: delay,
        z_index: 999,
        placement: {
            from: "bottom"
        },
        animate: {
            enter: 'animated fadeInDown',
            exit: 'animated fadeOutUp'
        },
        type: type
    });
}
