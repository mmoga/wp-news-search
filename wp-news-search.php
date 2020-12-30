<?php
/**
 * Plugin Name: News Search Plugin
 * Plugin URI: https://github.com/mmoga/...
 * Description: Add a form to search for news articles.
 * Version: 0.1
 * Text Domain: wp-news-search
 * Author: Matthew Mogavero
 * Author URI: https://mogavero.dev
 **/

defined('ABSPATH') or die('Ah ah ah. You didn\'t say the magic word.');

class News_search {

    // Load scripts upon class initiation
    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_get_news_callback', array($this, 'get_news_callback'));
        add_action('wp_ajax_nopriv_get_news_callback', array($this, 'get_news_callback'));
        add_shortcode('wp_news_search', array($this, 'wp_news_search_form'));
    }

    function enqueue_scripts() {
        // Enqueue CSS
        wp_enqueue_style('wp-news-search', plugins_url('/css/wp-news-search.css', __FILE__));
        // Enqueue and localize JS
        wp_enqueue_script('ajax-script', plugins_url('/js/wp-news-search_query.js', __FILE__), array('jquery'), null, true);
        wp_localize_script('ajax-script', 'ajax_object',
            array('ajax_url' => admin_url('admin-ajax.php'),
                'security' => wp_create_nonce('my-string-shh'),
            ));
    }

    // Handle AJAX request
    function get_news_callback() {
        check_ajax_referer('my-string-shh', 'security');
        $keyword = isset($_POST['news-keyword']) ? $_POST['news-keyword'] : null;
        echo $keyword;
        die();

    }

    public function wp_news_search_form() {
        $content .= '<form id="wp-news-search__form">
                        <label>
                            <input type="text" name="news-keyword" placeholder="' . __('Enter keywords') . '" id="news-keyword" required>
                        </label>
                        <button id="wp-news-search__submit">' .
        __('Search for news', 'wp-news-search') .
            '</button>
                    </form>';
        $content .= '<p id="result"></p>';
        return $content;
    }

}

new News_search();