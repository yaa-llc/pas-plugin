(function ($) {
    'use strict';
    console.log("MASONRY LOADED");
    //init Masonry
    var $grid = $('.jbgrid').masonry({
        itemSelector: '.grid-item',
        columnWidth: '.grid-sizer',
        gutter: '.gutter-sizer',
        stagger: '0',
        initLayout: false,
        percentPosition: true
    });



    $grid.imagesLoaded().progress( function( instance, image ) {
        var result = image.isLoaded ? 'loaded' : 'broken';

        // console.log( 'image is ' + result + ' for ' + image.img.src );
    });

    $grid.imagesLoaded().fail( function() {
        console.log('all images loaded, at least one is broken');
        initLayout();
    });

    // $grid.css({visibility: 'hidden'});
    $grid.imagesLoaded().done( function( ) {

        initLayout();
        $('.jbloader').remove();
    });

    function initLayout() {
        $grid.css({visibility: 'visible'});
        $grid.masonry('revealItemElements',  $('.grid-item'));
        $grid.masonry('layout');
    }

    var hoverClass = getOldHvrClass();
    var timeout;
    function timer() {
        timeout =  setTimeout(enableHover, 100);
    }

    addListeners();
    function addListeners() {
        $('#jub-portfolio-filter>a').click(function (event) {
            clearTimeout(timeout)

            $('.grid-item').removeClass(hoverClass);

            event.preventDefault();

            var id = $(this).attr('id');


            $('.grid-item').each(function () {

                var dataCat = '' + $(this).data('category');
                var cats = dataCat.split(",");
                if (((cats === 0) || (cats.indexOf(id) === -1)) && (id !== 'all-items')) {
                    $(this).addClass('to-remove');
                    $(this).removeClass('to-show');
                } else {
                    $(this).removeClass('to-remove');
                    $(this).addClass('to-show');
                }
            });


            var $rem = $grid.find('.to-remove');
            var $add = $grid.find('.to-show');

            $add.each(function (i) {
                $grid.masonry('unignore', $add[i])
            });

            $rem.each(function (i) {
                $grid.masonry('ignore', $rem[i])
            });

            $grid.masonry('hideItemElements', $rem);
            $grid.masonry('revealItemElements', $add);

            $grid.masonry('layout');
        });
    }

    $grid.masonry('on', 'layoutComplete', function () {

        timer();
    });


    function enableHover() {
        $('.grid-item').addClass(hoverClass);
    }

    function getOldHvrClass() {
        var $first = $('.grid-item:first');
        var clazz = $first.attr('class')
        if (!clazz) return;
        var classList = $first.attr('class').split(/\s+/);
        for (var i = 0; i < classList.length; i++) {
            if (classList[i] !== 'grid-item') {
                return classList[i];
            }
        }
    }
})(jQuery);

