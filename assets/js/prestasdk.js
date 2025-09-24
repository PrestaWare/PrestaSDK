$(document).ready(function(){
    $(".grower").click(function(){
        var element = $(this);
        if(element.hasClass('open')){
            element.addClass('close').removeClass('open');
            element.parent().find('ul:first').slideUp();
        }
        else{
            $('.grower').not(element).addClass('close').removeClass('open');
            var elementUL = element.parent().find('ul:first');
            $('.list-group-item ul').not(elementUL).slideUp();

            element.addClass('open').removeClass('close');
            element.parent().find('ul:first').slideDown();
        }
    });
});

function toggleMenu(arrow) {
    var $arrow = $(arrow);
    var $menu = $arrow.closest('.wsdk-menu');
    var $info = $('#wsdk-info');

    $menu.toggleClass('wsdk-menu-closed');
    $info.toggleClass('wsdk-info-closed');
}

(function ($) {
    'use strict';

    var COOKIE_NAME = 'wapm_hide_admin_header';
    var COOKIE_MAX_AGE = 60 * 60 * 24 * 365; // one year

    /**
     * Read a cookie by name.
     * @param {string} name
     * @returns {string|null}
     */
    function getCookie(name) {
        var pattern = new RegExp('(?:^|; )' + name.replace(/([.$?*|{}()\[\]\\\/\+^])/g, '\\$1') + '=([^;]*)');
        var matches = document.cookie.match(pattern);
        return matches ? decodeURIComponent(matches[1]) : null;
    }

    /**
     * Store a cookie value with module defaults.
     * @param {string} name
     * @param {string} value
     */
    function setCookie(name, value) {
        document.cookie = name + '=' + encodeURIComponent(value) + '; path=/; max-age=' + COOKIE_MAX_AGE + '; SameSite=Lax';
    }

    $(function () {
        var $body = $('body');
        var $pageHead = $('.page-head').first();

        if (!$body.length || !$pageHead.length) {
            return;
        }

        if ($body.hasClass('wsdk-header-toggle-initialized')) {
            return;
        }

        $body.addClass('wsdk-header-toggle-initialized wsdk-admin-header-toggle');

        var $wrapper = $('<div>', {
            'class': 'wsdk-header-toggle-wrapper'
        });

        var $button = $('<button>', {
            type: 'button',
            'class': 'wsdk-header-toggle-button',
            'aria-expanded': 'true',
            'aria-label': 'Hide admin header',
            title: 'Hide admin header'
        });

        var $icon = $('<span>', {
            'class': 'wsdk-header-toggle-icon'
        }).text('×');

        $button.append($icon);
        $wrapper.append($button);

        var $helpButton = $pageHead.find('.breadcrumb, .page-breadcrumb').first();
        if ($helpButton.length && $helpButton.parent().length) {
            $wrapper.insertBefore($helpButton);
        } else {
            $pageHead.append($wrapper);
        }

        /**
         * Apply current toggle state to the UI.
         * @param {boolean} hidden
         */
        function applyState(hidden) {
            if (hidden) {
                $body.addClass('wsdk-hide-page-head');
                $button.attr('aria-expanded', 'false')
                    .attr('aria-label', 'Show admin header')
                    .attr('title', 'Show admin header');
                $icon.text('×');
            } else {
                $body.removeClass('wsdk-hide-page-head');
                $button.attr('aria-expanded', 'true')
                    .attr('aria-label', 'Hide admin header')
                    .attr('title', 'Hide admin header');
                $icon.text('×');
            }
        }

        var storedState = getCookie(COOKIE_NAME);
        applyState(storedState === '1');

        $button.on('click', function (event) {
            event.preventDefault();
            var shouldHide = !$body.hasClass('wsdk-hide-page-head');
            applyState(shouldHide);
            setCookie(COOKIE_NAME, shouldHide ? '1' : '0');
        });
    });
})(window.jQuery);

