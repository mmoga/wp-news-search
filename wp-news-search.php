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

// Hide the key from Git
include ".env.php";

// No direct access to the plugin—sorry!
defined('ABSPATH') or die('Ah ah ah. You didn\'t say the magic word.');

if (!class_exists('News_Search')) {

    class News_Search {

        // Properties
        private $news_source;

        // Constructor
        // Load scripts upon class initiation
        public function __construct() {
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
            add_action('wp_ajax_get_news_callback', array($this, 'get_news_callback'));
            add_action('wp_ajax_nopriv_get_news_callback', array($this, 'get_news_callback'));
            add_shortcode('wp_news_search', array($this, 'wp_news_search_form'));
        }
        // Enqueue scripts
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

        // Display the HTML search form
        public function wp_news_search_form() {
            // Wrap this in a container at some point
            {
                $content .= '<form id="wp-news-search__form">
            <label for="news-keyword">
            Search by keywords
            <input type="text" id="news-keyword" name="news-keyword" placeholder="' . __('Enter keywords') . '" required>
            </label>
            <button id="wp-news-search__submit">' .
                __('Search for news', 'wp-news-search') .
                    '</button>
            </form>';
            }

            // Show result on page
            $content .= '<div id="result"></div>';
            return $content;
        }

        // Handle AJAX request
        function get_news_callback() {
            check_ajax_referer('my-string-shh', 'security');

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            if (isset($_POST['data'])) {
                $form_data = ($_POST['data']);
                $keyword = trim($form_data['keyword']);

                $endpoint = 'https://newsapi.org/v2/everything?q=' . rawurlencode($keyword) . '&domains=' . esc_html__( /* source */) . '.com';
                $options = [
                    'headers' => array(
                        'Content-Type' => 'application/json',
                        'X-Api-Key' => getenv('NEWS_API_KEY'),
                    ),
                ];
                $response = wp_remote_get($endpoint, $options);
                $response_body = wp_remote_retrieve_body($response);
                $result = json_decode($response_body);

                if (is_array($result) && !is_wp_error($result)) {
                    $error_message = $result->get_error_message();
                    echo "Something went wrong: $error_message";
                } else {
                    // Return the decoded JSON to jQuery
                    echo '<pre>';
                    print_r($result);
                    echo '</pre>';
                    echo $endpoint;
                }

            }

            die();

        }
    }

    new News_Search();
}