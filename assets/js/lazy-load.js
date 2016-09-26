var LazyLoad = {
	init: function() {
        /* Masquer la pagination si Javascript activé */
        $(".no-ajax").hide();

        /* container */
        // var container  = $(".js-load-more");
        var container  = $(lazy_load.containerClass);

        if(container.length) {

            var ajaxParams  = {
                action: 'get_ajax_content',
                template: container.data("template"),
            };

            var currentPage = lazy_load.queryVars.paged;

            if( typeof(currentPage) == "undefined" || currentPage === null ) {
                currentPage = 1;
            }

            var locked = false;

            $(document).scroll(function() {
                var top   = $(this).scrollTop() + $(window).height();

                if(lazy_load.triggerOffset === 'auto'){
                    var offset = container.outerHeight()*lazy_load.triggerOffsetAuto/100;
                }
                else{
                    var offset = lazy_load.triggerOffset;
                }

                var limit = container.offset().top + container.outerHeight() - offset;
                // var limit = container.offset().top + container.outerHeight() - (container.outerHeight()*30/100);

                if (top > limit && ! locked && currentPage < lazy_load.maxPages) {
                    locked = true;
                    var data = ajaxParams;
                    $.extend(data,lazy_load.queryVars);
                    data.paged = ++currentPage;

                    var success = function( response, status ) {
                        if( "success" == status && response.length && '0' != response) {
                            $(response).hide().appendTo(container).fadeIn(200);
                            locked = false;
                        } else {
                            locked = true;
                        }
                    }

                    var error = function( jqXHR, status, error ) {
                        locked = true;
                    }

                    $.ajax({
                        url: lazy_load.url,
                        method: "GET",
                        data: data,
                        success : success,
                        error: error
                    });
                }
            });
        }
    }
};

jQuery( function($) {
    LazyLoad.init();
});