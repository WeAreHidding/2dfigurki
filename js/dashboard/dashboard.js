jQuery(document).ready(function() {
    jQuery("#sidebar").mCustomScrollbar({
        theme: "minimal"
    });

    jQuery('#sidebarCollapse').on('click', function () {
        jQuery('#sidebar, #content').toggleClass('active');
        jQuery('.collapse.in').toggleClass('in');
        jQuery('a[aria-expanded=true]').attr('aria-expanded', 'false');
    });
});