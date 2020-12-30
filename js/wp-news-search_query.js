// Using https://newsapi.org/docs/authentication

(function ($) {

    $(document).ready(function () {
        $('#wp-news-search__form').submit(function (event) {
            event.preventDefault();

            // const keyword = $('#query-input').val();
            const values = $(this).serialize();


            if (values) {
                // send stuff to php
                console.log(values);
                const data = {
                    action: 'get_news_callback',
                    status: 'enabled',
                    security: ajax_object.security,
                    form_data: values
                }

                $.post(ajax_object.ajax_url, data, function(response) {
                    if (response) {
                        console.log(`Response is: ${response}`);
                    }
                })
                    .fail(() => { console.error('error'); })
                    .always(() => { console.log('form submitted') });
            }

        });


    });

})(jQuery);
