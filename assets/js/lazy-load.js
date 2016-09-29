var LazyLoad = {
    container: $(lazy_load.containerClass),
    ajaxParams: {
                    action: 'get_ajax_content',
                    template: $(lazy_load.containerClass).data("template"),
                },
    currentPage: lazy_load.queryVars.paged,
    locked: false,

	init: function() {
        /* Masquer la pagination si Javascript activé */
        $(".no-ajax").hide();

        if(LazyLoad.container.length) {

            if( typeof(LazyLoad.currentPage) == "undefined" || LazyLoad.currentPage === null ) {
                LazyLoad.currentPage = 1;
            }

            if(LazyLoad.container.is('div')){
                $(document).scroll(function() {
                    var top   = $(this).scrollTop() + $(window).height();

                    if(lazy_load.triggerOffset === 'auto'){
                        var offset = LazyLoad.container.outerHeight()*lazy_load.triggerOffsetAuto/100;
                    }
                    else{
                        var offset = lazy_load.triggerOffset;
                    }

                    var limit = LazyLoad.container.offset().top + LazyLoad.container.outerHeight() - offset;

                    if (top > limit) {
                        LazyLoad.loadContent(LazyLoad.container);
                    }
                });

            }
            else if(LazyLoad.container.is('button')){
                LazyLoad.button        = LazyLoad.container;
                LazyLoad.wrapper       = $('#' + LazyLoad.button.data('wrapper'));
                LazyLoad.button.on('click', function() { 
                    LazyLoad.loadContent(LazyLoad.wrapper);
                });
            }

        }
    },
    loadContent: function(container) {
        if(! LazyLoad.locked && LazyLoad.currentPage < lazy_load.maxPages){
            LazyLoad.locked = true;
            var data = LazyLoad.ajaxParams;
            $.extend(data,lazy_load.queryVars);
            data.paged = ++LazyLoad.currentPage;

            var success = function( response, status ) {
                if("success" == status && response.length && '0' != response) {
                    $(response).hide().appendTo(container).fadeIn(200);
                    LazyLoad.locked = false;
                } else {
                    LazyLoad.locked = true;
                }
            }

            var error = function( jqXHR, status, error ) {
                LazyLoad.locked = true;
            }

            $.ajax({
                url: lazy_load.url,
                method: "GET",
                data: data,
                success : success,
                error: error
            });
        }
    }
};

jQuery( function($) {
    LazyLoad.init();
});