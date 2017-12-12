(function ($) {
    'use strict';
    $('.no-items').hide();
    if (!checkForItems()) {
        $('.no-items').show();
        return;
    }
    $('.no-items').hide();
    initColorPicker();
    initShortCodeGenerator();
    createTabs();
    initGridPreviewer();

    //Free version
    disableOptions();

    function checkForItems() {
        //quick fix
        var s = 'item-col-width';
        var $items = $("[id*=" + s + "]");
        return ($items.length > 0);
    }

    function disableOptions() {
        $('#hover_class').prop('disabled', true);
        $('#hover_class').next('.description').html('<a href="https://codecanyon.net/item/jb-portfolio-a-lightweight-portfolio-plugin-for-wordpress/19381634" style="color: red;">Only available in the pro version</a>');
        $('#hide_filter').prop('disabled', true);
        $('#hide_filter').next('.description').html('<a href="https://codecanyon.net/item/jb-portfolio-a-lightweight-portfolio-plugin-for-wordpress/19381634" style="color: red;">Only available in the pro version</a>');
        $('#cols_tablet').prop('disabled', true);
        $('#cols_tablet').next('.description').html('<a href="https://codecanyon.net/item/jb-portfolio-a-lightweight-portfolio-plugin-for-wordpress/19381634" style="color: red;">Only available in the pro version</a>');
        $('#cols_mobile').prop('disabled', true);
        $('#cols_mobile').next('.description').html('<a href="https://codecanyon.net/item/jb-portfolio-a-lightweight-portfolio-plugin-for-wordpress/19381634" style="color: red;">Only available in the pro version</a>');
        $('#cols_mobile').next('.description').html('<a href="https://codecanyon.net/item/jb-portfolio-a-lightweight-portfolio-plugin-for-wordpress/19381634" style="color: red;">Only available in the pro version</a>');
        $('#jub-gallery-form > ul > li:nth-child(4)').html('<a href="https://codecanyon.net/item/jb-portfolio-a-lightweight-portfolio-plugin-for-wordpress/19381634" style="color: red;">Items (Only available in the pro version)</a>');
        $('#jub-gallery-form > ul > li:nth-child(4)').click(function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
        $('#tab-4 > table').css('display', 'none');
        $('#enable_lightbox').prop('disabled', true);
        $('#enable_lightbox').next('.description').html('<a href="https://codecanyon.net/item/jb-portfolio-a-lightweight-portfolio-plugin-for-wordpress/19381634" style="color: red;">Only available in the pro version</a>');

    }

    function initColorPicker() {
        var cpOptions = {
            // you can declare a default color here,
            // or in the data-default-color attribute on the input
            defaultColor: false,
            // a callback to fire whenever the color changes to a valid color
            change: function (event, ui) {
            },
            // a callback to fire when the input is emptied or an invalid color
            clear: function () {
            },
            // hide the color picker controls on load
            hide: true,
            // show a group of common colors beneath the square
            // or, supply an array of colors to customize further
            palettes: true
        };

        $('.color-picker').wpColorPicker(cpOptions);
    }


    function initShortCodeGenerator() {
        //SHORTCODE GENERATOR
        var $copyButton = $('#copy');
        var $generateButton = $('#generate-shortcode');
        var $result = $('#shortcode_result');

        $copyButton.click(copyToClipboard);
        $generateButton.click(generateShortcode);
        $result.hide();
        $copyButton.hide();

        function generateShortcode() {
            var shortcode = parseForm();

            $result.show();
            $result.val(shortcode);
            $copyButton.show();
        }

        function parseForm() {
            var shortcode = '[jub-portfolio ';
            $("form#jub-gallery-form :input").not(':button, :submit, #_wpnonce').each(function () {
                var input = $(this);

                //exclude inputs
                if ((input.attr('name') !== 'action') && (input.attr('name') !== 'option_page') && (input.attr('id') !== 'shortcode_result') && (input.attr('id') !== undefined)) {
                    //handle checkbox values
                    var value = '';
                    if (input.attr('type') === 'checkbox') {
                        value = input.attr('checked') ? 1 : 0;
                    } else {
                        value = input.val();
                    }

                    shortcode += input.attr('id') + '="' + value + '" ';
                }
            });
            shortcode += ']';


            return shortcode;
        }

        function copyToClipboard() {
            var text = $result.val();
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(text).select();
            document.execCommand("copy");
            $temp.remove();
        }
    }

    function createTabs() {
        var lastTab = localStorage.getItem('jub_last_tab') || 'tab-1';
        $('form#jub-gallery-form').prepend('<ul class="tabs"></ul>');
        $("form#jub-gallery-form table").each(function (i) {
            var currentTab = 'tab-' + (i + 1);
            var tabLinkClass = currentTab === lastTab ? "tab-link current" : "tab-link";
            $('.tabs').append('<li class="' + tabLinkClass + '" data-tab="' + currentTab + '">Tab One</li>');

            //set headline text to tab
            var $headline = $(this).prev();
            var t = $headline.text();

            $('.tabs :nth-child(' + (i + 1) + ')').html(t);
            $headline.hide();

            var clazz = currentTab === lastTab ? 'tab-content current' : 'tab-content';

            $(this).wrap('<div id="' + currentTab + '" class="' + clazz + '"></div>');
        });

        $('.jub-settings-wrapper').fadeIn('fast');


        $('ul.tabs li').click(function () {

            var tab_id = $(this).attr('data-tab');

            $('ul.tabs li').removeClass('current');
            $('.tab-content').removeClass('current');

            $(this).addClass('current');
            $("#" + tab_id).addClass('current');
            localStorage.setItem('jub_last_tab', tab_id);
        });
    }


    function initGridPreviewer() {
        var $refreshPreviewButton = $('#refresh-preview');
        var $hidePreviewButton = $('#hide-preview');
        var $formContainer = $('.form-container');
        var $gridContainer = $('.grid-container');

        $refreshPreviewButton.click(createGridPreview);
        $hidePreviewButton.click(hidePreview);
        $hidePreviewButton.hide();

        function hidePreview() {
            $formContainer.show();
            $gridContainer.hide();
            $refreshPreviewButton.show();
            $hidePreviewButton.hide();
        }

        //init Masonry
        var $grid = $('.grid').masonry({
            itemSelector: '.grid-item',
            columnWidth: '.grid-sizer',
            gutter: '.gutter-sizer',

            initLayout: true,
            percentPosition: true
        });

        function calcWidth(itemCols, cols, gutter) {
            var m = 0;
            if (itemCols > 1) {
                m = ((itemCols - 1) * gutter);
            }
            return (((100 - ((cols - 1) * gutter)) / cols) * itemCols) + m;
        }

        function createGridPreview() {
            $formContainer.hide();
            $gridContainer.show();
            $hidePreviewButton.show();
            $refreshPreviewButton.hide();

            var $gridcontainer = $('.grid');

            $gridcontainer.empty();
            var gutterSizer = '<div class="gutter-sizer"></div>';
            var gridSizer = '<div class="grid-sizer"></div>';

            $gridcontainer.append(gutterSizer);
            $gridcontainer.append(gridSizer);

            var s = 'item-col-width';
            var $items = $("[id*=" + s + "]");

            var backgroundColor = $('#background_color').val();
            var gridCols = $('#cols').val();
            var gutter = $('#x_dist').val();
            var marginBottom = $('#y_dist').val();
            var gridWidth = calcWidth(1, gridCols, gutter);


            $('.gutter-sizer').css({'width': gutter + '%'});
            $('.grid-sizer').css({'width': gridWidth + '%'});

            $.each($items, function () {

                var data = JSON.parse($(this).attr('data-value'));


                var $backgroundColorItem = $('#item-background-color' + data[1]);

                var itemBackgroundColor = $backgroundColorItem.val() === '' ? backgroundColor : $backgroundColorItem.val();

                var imageData = data[0];
                var url = imageData[0];

                var itemCols = $(this).val();
                var width = calcWidth(itemCols, gridCols, gutter);

                if (url) {
                    $grid.append('<div style="background-color:' + itemBackgroundColor + '; margin-bottom:' + marginBottom + '%; width:' + width + '%;" class="grid-item"><img class="img" src="' + url + '"></img></div>');
                }

            });

            $grid.imagesLoaded().done(function () {
                $grid.masonry('reloadItems');
                $grid.masonry('layout');

            });
        }
    }

})(jQuery);
