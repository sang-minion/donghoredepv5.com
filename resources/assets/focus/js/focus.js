/**
 * Created by Bui on 11/06/2017.
 */
jQuery(document).ready(function () {
    HOME.goback();
    HOME.scrolltoTop();
});
HOME = {
    goback: function () {
        jQuery(document).on('click', '#goback', function () {
            window.history.back();
        });
    },
    scrolltoTop: function () {
        jQuery(window).resize(function () {
            jQuery(".navbar-left").show();
        });
        jQuery("#app").on('scroll', function () {
            var e = jQuery("#app").scrollTop();
            var h = jQuery(window).width();
            if (e > 300) {
                jQuery(".navbar-default").addClass("navbar-fixed-top");
                if(h<992){
                    jQuery(".navbar-left").hide();
                }
                jQuery(".btn-top").show();
            } else {
                jQuery(".navbar-default").removeClass("navbar-fixed-top");
                if(h<992){
                    jQuery(".navbar-left").show();
                }
                jQuery(".btn-top").hide();
            }
            if(h<768){
                jQuery(".navbar-left").show();
            }
        });
        jQuery(".btn-top").on('click',function () {
            jQuery("#app").animate({
                scrollTop: 0
            })
        });
    },
};

