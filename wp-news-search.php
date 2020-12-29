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

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

 class News_search {

  public function __construct() {
      add_action( 'wp_enqueue_scripts', array($this, 'enqueue_scripts'));
      add_shortcode('wp_news_search', array($this, 'wp_news_search_form'));
  }

  function enqueue_scripts() {
    wp_enqueue_style( 'wp-news-search', plugins_url('/css/wp-news-search.css', __FILE__) );
    
    wp_enqueue_script( 'wp-news-search', plugins_url('/js/wp-news-search.js', __FILE__), array('jquery'), null, true );
  }
      
  public function wp_news_search_form($atts) {
    $content .= '<form id="wp-news-search__form">
                  <label>
                    <input type="text" name="query" placeholder="'. __('Enter keywords') .'" id="query-input" required>
                  </label>
                  <button id="wp-news-search__submit">' . 
                    __( 'Search for news', 'wp-news-search' ) . 
                  '</button>
                </form>';
    $content .= '<p id="update"></p>';
    return $content;
  }
 }

 new News_search();