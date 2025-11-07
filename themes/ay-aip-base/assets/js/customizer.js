(function ($) {
    function initFontSelects() {
        $('.ay-font-select-control').each(function () {
            var $select = $(this);
            if ($select.data('select2')) {
                return;
            }
            $select.selectWoo({
                width: '100%',
                placeholder: $select.data('placeholder') || ''
            });
        });
    }

    wp.customize.bind('ready', initFontSelects);
    $(document).on('DOMNodeInserted', initFontSelects);
})(jQuery);
