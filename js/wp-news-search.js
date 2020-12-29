// Using https://newsapi.org/docs/authentication

(function ($) {

    $(document).ready(function () {
        $('#wp-news-search__form').submit(function (event) {
            event.preventDefault();
               
            const keyword = $('#query-input').val();

            if (keyword) {
                // send stuff to php
                console.log('sent');
            }

        });


    });

})(jQuery);
