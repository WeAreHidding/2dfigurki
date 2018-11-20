
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

    switch(id) {
        case 'menu-home': loadContent("home"); break;
        case 'menu-design': loadContent("design"); break;
        case 'menu-dash': loadContent("dash"); break;
        case 'menu-stats': loadContent("stats"); break;
        default: console.log('error'); break;
    }
}

function loadContent(content) {
    jQuery('#content_home').css("display", "none");
    jQuery('#content_dash').css("display", "none");
    jQuery('#content_design').css("display", "none");
    jQuery('#content_stats').css("display", "none");

    jQuery('#content_'+content).css("display", "block");
}
