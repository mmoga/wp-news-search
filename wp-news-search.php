<?php
/**
 * Plugin Name: News Search Plugin
 * Plugin URI: https://github.com/mmoga/...
 * Description: Add a form to search for news articles.
 * Version: 1.0.0
 * Text Domain: wp-news-search
 * Author: Matthew Mogavero
 * Author URI: https://mogavero.dev
 **/

/**
 * Hide the key from Git.
 */
include ".env.php";

/**
 * No direct access to the plugin—sorry!
 */
defined('ABSPATH') or die('Ah ah ah. You didn\'t say the magic word.');

if (!class_exists('News_Search')) {
    /**
     * News Search main class.
     *
     * @author: Matthew Mogavero
     */
    class News_Search {
        /**
         * The single instance of the class.
         *
         * @var News_Search
         */
        private static $instance = null;

        /**
         * The news source.
         *
         * @var string
         */
        public $outlet = "";

        /**
         * Plugin settings.
         *
         * @var array
         */
        public $settings = array();

        /**
         * News Search Constructor.
         */
        public function __construct() {
            $this->settings = array(
                'plugin_path' => plugin_dir_path(__FILE__),
                'plugin_url' => plugin_dir_url(__FILE__),
                'plugin_base' => dirname(plugin_basename(__FILE__)),
                'plugin_base_url' => plugin_basename(__FILE__),
                'plugin_file' => __FILE__,
                'plugin_version' => '1.0.0',
            );

            $this->run_plugin();
        }

        /**
         * Singleton.
         *
         * @return self Main instance.
         */
        public static function init() {
            if (is_null(self::$instance)) {
                self::$instance = new self();
            }
            return self::$instance;
        }

        /**
         * Main plugin function.
         *
         * @since 1.0.0
         */
        public function run_plugin() {
            add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
            add_action('wp_ajax_get_news_callback', array($this, 'get_news_callback'));
            add_action('wp_ajax_nopriv_get_news_callback', array($this, 'get_news_callback'));
            add_shortcode('wp_news_search', array($this, 'wp_news_shortcode'));
        }

        /**
         * Enqueue scripts.
         *
         * @since 1.0.0
         */
        function enqueue_scripts() {
            // Enqueue CSS
            wp_enqueue_style('wp-news-search', plugins_url('css/wp-news-search.css', __FILE__));
            // Enqueue and localize JS
            wp_enqueue_script('ajax-script', plugins_url('js/wp-news-search_query.js', __FILE__), array('jquery'), null, true);
            wp_localize_script('ajax-script', 'ajax_object',
                array('ajax_url' => admin_url('admin-ajax.php'),
                    'security' => wp_create_nonce('my-string-shh'),
                ));
        }

        /**
         * News Search shortcode. Adds form to page and retrieves attributes.
         *
         * @since 1.0.0
         * @param array $atts An associative array of attributes.
         * @return string
         */
        public function wp_news_shortcode($atts) {
            $atts = shortcode_atts(
                array(
                    'outlet' => 'google',
                ),
                $atts
            );
            $outlet = $atts['outlet'];
            set_transient('_outlet', $outlet, 12 * HOUR_IN_SECONDS);

            $content = '';
            $view = $this->get_view_path('form.php');
            if (file_exists($view)) {
                ob_start();
                include $view;
                $content = ob_get_clean();
            }
            return $content;
        }

        /**
         * Get the path to view.
         *
         * @param string $view_name View name.
         * @param string|boolean $sub_dir_name The sub-directory.
         * @return string
         */
        public function get_view_path($view_name, $sub_dir_name = false) {
            $path = $rel_path = '';
            $plugin_base = 'wp-news-search';
            if (!empty($sub_dir_name)) {
                $rel_path .= "/{$sub_dir_name}";
            }
            $rel_path .= "/{$view_name}";
            $path = $this->settings['plugin_path'] . 'views' . $rel_path;
            return $path;
        }

        /**
         * Handle AJAX request.
         */
        public function get_news_callback() {
            check_ajax_referer('my-string-shh', 'security');

            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            if (isset($_POST['data'])) {
                $form_data = ($_POST['data']);
                $keyword = trim($form_data['keyword']);
                $outlet = get_transient('_outlet');

                $endpoint = 'https://newsapi.org/v2/everything?q=' . rawurlencode($keyword) . '&domains=' . esc_html__($outlet) . '.com';
                $options = [
                    'headers' => array(
                        'Content-Type' => 'application/json',
                        'X-Api-Key' => getenv('NEWS_API_KEY'),
                    ),
                ];
                $response = wp_remote_get($endpoint, $options);
                $response_body = wp_remote_retrieve_body($response);
                $result = json_decode($response_body);
                $articles = $result->articles;

                if (is_array($result) && !is_wp_error($result)) {
                    $error_message = $result->get_error_message();
                    echo "Something went wrong: $error_message";
                } elseif (empty($result->articles)) {
                    echo 'No articles found. 😔';

                } else {
                    $content = '';
                    $view = $this->get_view_path('article.php');
                    if (file_exists($view)) {
                        ob_start();
                        include $view;
                        $content = ob_get_clean();
                    }
                    echo $content;
                }
            }

            die();

        }
    }

    News_Search::init();
}