<?php
/**
 * Search form.
 *
 * @package wp-news-search
 */

?>

<form id="wp-news-search__form">
    <label for="news-keyword">
        Search by keywords
        <input type="text" id="news-keyword" name="news-keyword" placeholder="<?php echo __('Enter keywords') ?>"
            required>
    </label>
    <button id="wp-news-search__submit"><?php echo __('Search for news', 'wp-news-search') ?></button>
</form>

<div id="wp-news-search-results"></div>