
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

function menuToggle(a) {

    jQuery(".components li").each(function() {
        jQuery(this).removeClass('active');
    });
    jQuery(a).toggleClass('active');
    var id=jQuery(a).attr('id');
    var content=jQuery('#content_main');

    switch(id) {
        case 'menu-home': content.load('/tpl/room/home.html'); break;
        case 'menu-design': content.load('/tpl/room/design.html'); break;
        case 'menu-dash': content.load('/tpl/room/dash.html', function () {
            jQuery('.js-example-basic-single').select2();
        }); break;
        case 'menu-stats': content.load('/tpl/room/stats.html'); break;
        default: console.log('error'); break;
    }
}
