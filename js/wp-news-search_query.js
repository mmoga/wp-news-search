// Using https://newsapi.org/docs/authentication

(function ($) {
    $(document).ready(function () {
        const result = $('#wp-news-search-results');
        const button = $('#wp-news-search__form button');

        $('#wp-news-search__form').submit(function (event) {
            event.preventDefault();

            button.attr('disabled', true);

            const form_data = {
                keyword: $('#news-keyword').val(),
                // source: $('#news-source').val(),
            }


            if (form_data) {
                // send stuff to php
                const data = {
                    action: 'get_news_callback',
                    status: 'enabled',
                    dataType: 'json',
                    security: ajax_object.security,
                    data: form_data
                }

                $.post(ajax_object.ajax_url, data, function (response) {
                    if (response) {
                        result.html(response);
                    }
                })
                .fail(() => { 
                    result.html('We\'re experiencing technical difficulties. 🙁');
                     })
                    .always(() => { button.attr('disabled', false); });
            }

        });


    });

})(jQuery);
