$(document).ready(function () {

    /* start select script */

    $('select').each(function () {
        var $this = $(this), numberOfOptions = $(this).children('option').length;

        $this.addClass('select-hidden');
        $this.wrap('<div class="select"></div>');
        $this.after('<div class="select-styled"></div>');

        var $styledSelect = $this.next('div.select-styled');
        $styledSelect.text($this.find('option:selected').text());

        var $list = $('<ul />', {
            'class': 'select-options'
        }).insertAfter($styledSelect);

        for (var i = 0; i < numberOfOptions; i++) {
            if ($this.children('option').eq(i).val() != "more") {
                $('<li />', {
                    text: $this.children('option').eq(i).text(),
                    rel: $this.children('option').eq(i).val()
                }).appendTo($list);
            }
        }

        var $listItems = $list.children('li');

        $styledSelect.click(function (e) {
            e.stopPropagation();
            $('div.select-styled.active').not(this).each(function () {
                $(this).removeClass('active').next('ul.select-options').hide();
            });
            $(this).toggleClass('active').next('ul.select-options').toggle();
        });

        $listItems.click(function (e) {
            $this.find("option").removeAttr("selected");
            $listItems.removeClass('li_act');
            $(this).addClass('li_act');
            e.stopPropagation();
            $styledSelect.text($(this).text()).removeClass('active');
            $this.val($(this).attr('rel'));
            $list.hide();
            $this.children().each(function () {
                if ($this.val() == $(this).val()) {
                    $(this).attr('selected', '');
                }
            });
        });

        $(document).click(function () {
            $styledSelect.removeClass('active');
            $list.hide();
        });

    });

    /* end select script */

    /*--- Flicity ---*/

    let banner_slide = $(".banner_slide"),
        news_slide   = $(".news_slide"),
        viewed_slide = $(".viewed_slide").length,
        item_slide   = $(".item_slide").length;

    if (banner_slide.length) {
        banner_slide.flickity({
            wrapAround: true,
            autoPlay: 5000,
            imagesLoaded: true,
            arrowShape: "M15.1426 1.71436L0.856864 16.0001L15.1426 30.2858"
        });
    }

    if (news_slide.length) {
        news_slide.flickity({
            contain: true,
            pageDots: false,
            wrapAround: true,
            cellAlign: 'left',
            imagesLoaded: true,
            arrowShape: "M15.1426 1.71436L0.856864 16.0001L15.1426 30.2858"
        });
    }

    if (viewed_slide) { // Недавно просмотренные

        let flktySelector   = '.viewed_slide',
            flktyItems      = '.view-recently',
            checkWrapResult = checkWrap(flktySelector, flktyItems),
            flktyOptions    = {
                wrapAround: checkWrapResult,
                draggable: checkWrapResult,
                cellAlign: 'left',
                contain: true,
                prevNextButtons: true,
                pageDots: false,
                imagesLoaded: true,
                arrowShape: "M15.1426 1.71436L0.856864 16.0001L15.1426 30.2858"
            };

        let flkty = new Flickity(flktySelector, flktyOptions);

        if (checkWrapResult === false)
            $(flktySelector+' .flickity-button').attr('disabled', '');

        window.addEventListener('resize', (ev) => {
            if ('destroy' in flkty) {
                checkWrapResult = checkWrap(flktySelector, flktyItems);

                flkty.destroy();
                flktyOptions.wrapAround = checkWrapResult;
                flktyOptions.draggable  = checkWrapResult;
                flkty = new Flickity(flktySelector, flktyOptions);

                if (checkWrapResult === false)
                    $(flktySelector+' .flickity-button').attr('disabled', '');
            }
        });

    }

    if (item_slide) { // Рекомендации

        let flktySelector   = '.item_slide',
            flktyItems      = '.el_item',
            checkWrapResult = checkWrap(flktySelector, flktyItems),
            flktyOptions    = {
                wrapAround: checkWrapResult,
                draggable: checkWrapResult,
                cellAlign: 'left',
                contain: true,
                prevNextButtons: true,
                pageDots: false,
                imagesLoaded: true,
                arrowShape: "M15.1426 1.71436L0.856864 16.0001L15.1426 30.2858"
            };

        let flkty = new Flickity(flktySelector, flktyOptions);

        if (checkWrapResult === false)
            $(flktySelector+' .flickity-button').attr('disabled', '');

        window.addEventListener('resize', (ev) => {
            if ('destroy' in flkty) {
                checkWrapResult = checkWrap(flktySelector, flktyItems);

                flkty.destroy();
                flktyOptions.wrapAround = checkWrapResult;
                flktyOptions.draggable  = checkWrapResult;
                flkty = new Flickity(flktySelector, flktyOptions);

                if (checkWrapResult === false)
                    $(flktySelector+' .flickity-button').attr('disabled', '');
            }
        });

    }

    /*--- Flicity [END] ---*/


    $(".popup_size").click(function () {
        $(".table_sizes").fadeIn();
    });

    $(".close_table_sz").click(function () {
        $(".table_sizes").fadeOut();
    });

    // dd-menu (gender) (top-bar)
    $('.dd-menu-button').click(function () {

        let el = $(this).closest('.dd-menu');

        if (!el.hasClass('open'))
            el.addClass('open');
        else
            el.removeClass('open');


    });

    $(document).mousedown(function (e) {

        var block = $(".dd-menu");

        if (!block.is(e.target) && block.has(e.target).length === 0) {
            block.removeClass('open');
        }

    });

    /*----- POPUP -----*/
    $('.popup-toggle').click(function () {

        let $body = $('body'),
            $popupSelector = '.popup[data-popup-name="' + $(this).data('popup-name') + '"]',
            $popup = $($popupSelector);

        $body.addClass('popup-open');

        $popup.show();
        $popup.find('.popup-content').show();
        $popup.css('display', 'block');

        setTimeout(function () {

            $popup.addClass('open');

        }, 1);

        $($popupSelector + ', ' + $popupSelector + ' .popup-close').click(function (e) {

            if ($(e.target).hasClass('popup') || $(e.target).hasClass('popup-wrapper') || $(this).hasClass('popup-close')) {

                $body.removeClass('popup-open');
                $popup.removeClass('open');

                setTimeout(function () {

                    $popup.hide();
                    $popup.css('display', 'none');

                }, 300);

            }

        })

    });

    /*----- AUTH TOGGLE -----*/
    $('.auth-toggle').click(function () {
        let layout = $('#' + $(this).data('auth-toggle'));

        $('.auth > div').hide();
        layout.show();
    });


    /*----- PHONE MASK -----*/
    var phoneMask = IMask(
        document.getElementsByClassName('phone-mask')[0], {
            mask: '+{7} (000) 000 00 00',
            overwrite: true,
            autofix: true,
            lazy: false
        });

    phoneMask.on("accept", function () {
        $(phoneMask.el.input).addClass('active');
        if (phoneMask._unmaskedValue === '7') {
            $(phoneMask.el.input).removeClass('active');
        }
    });

    /*----- CODE MASK -----*/
    var codeMask = IMask(
        document.getElementsByClassName('phone-auth-code')[0], {
            mask: '00000',
            overwrite: true,
            autofix: true,
            lazy: false,
            placeholderChar: '0'
        });

    codeMask.on("accept", function () {
        $(codeMask.el.input).addClass('active');
        if (phoneMask._unmaskedValue === '') {
            $(codeMask.el.input).removeClass('active');
        }
    });


    /*----- RADIO BUTTON -----*/
    $(".gender_input").click(function () {
        let radio = $(this).find('input[type="radio"]'),
            radio_name = radio.attr('name');

        $('input[name="' + radio_name + '"]').prop('checked', false);

        radio.prop('checked', true);

    });

    /*---- MOBILE MENU ----*/
    $('.menu_button').click(function () {
        let el = $(this),
            menu = $('.menu-mobile');

        if (!el.hasClass('open')) {
            el.addClass('open');
            menu.addClass('open');
        } else {
            el.removeClass('open');
            menu.removeClass('open');
        }

        $(document).mouseup(function (e) {
            var div = $(".menu-mobile, .menu_button");
            if (!div.is(e.target)
                && div.has(e.target).length === 0) {
                el.removeClass('open');
                menu.removeClass('open');
            }
        });

    });

    /*--- SmartFilter in mobile ---*/

    var bxFilter = $('.bx-filter'),
        smartFilterPC = $('.bx-smartfilter-wrapper'),
        smartFilterMobile =  $('.mobile-smartfilter'),
        filterMobStatus = 0;

    if ($(window).width() < 992) {

        smartFilterPC.empty();
        smartFilterMobile.append(bxFilter);
        filterMobStatus = true;

    }

    $(document).ready(function () {

        $(window).resize(function () {

            if ($(window).width() < 992) {

                if (!filterMobStatus) {
                    smartFilterPC.empty();
                    smartFilterMobile.append(bxFilter);
                    filterMobStatus = true;
                }

            } else {

                if (filterMobStatus) {
                    smartFilterMobile.empty();
                    smartFilterPC.append(bxFilter);
                    filterMobStatus = false;

                    $('body, .popup-filter').removeClass('popup-open open');
                    $('.popup-filter .popup-content').hide();
                    $('.popup-filter').css('display', 'none');
                }

            }
        });
    });

    $('.product-item-scu-item-color-container.notallowed').click(function (e) {
        e.preventDefault();
    });


});

function add2wish(p_id, pp_id, p, name, dpu, th) {
    $.ajax({
        type: "POST",
        url: "/local/ajax/fav.php",
        data: "p_id=" + p_id + "&pp_id=" + pp_id + "&p=" + p + "&name=" + name + "&dpu=" + dpu,
        success: function (html) {
            $(th).addClass('in_wishlist');
            $('#wishcount').html(html);
        }
    });
}

function checkWrap(carouselSelector = '.carousel', cellSelector = '.carousel-cell') {
    // if sum(carousel-cell width) > carousel width then wrap else not
    const carousel = document.querySelector(carouselSelector);
    const cells = carousel.querySelectorAll(cellSelector);

    if (carousel && cells) {
        let cellsTotalWidth = 0;
        cells.forEach((cell) => {
            const style = window.getComputedStyle(cell);
            cellsTotalWidth += parseFloat(style.width) +
                parseFloat(style.marginRight) +
                parseFloat(style.marginLeft);
        });
        const carouselWidth = parseFloat(window.getComputedStyle(carousel).width);

        if (cellsTotalWidth > carouselWidth) {
            return true;
        } else {
            return false;
        }
    }

    return false;
}