<?php
/**
 * Plugin Name: Stewardship App
 * Plugin URI: http://stewardshipapp.com
 * Description: Manage Tithes, Members, Staff, Small Groups within the cloud. Be a good steward of God's gifts.
 * Version: 1.0
 * Author: James Hammack
 * Author URI: http://cwinno.com
 * License: The 
 */
 
if ( ! defined( 'ABSPATH' ) ) {
    exit; // disable direct access
}
 

function add_slider_css() {

	// Register style.css
	wp_register_style('slider-styles', plugins_url('/lib/slider-style.css', __FILE__));
	wp_enqueue_style('slider-styles');
	
}

require_once('inc/sliderpost.php');
require_once('cwi-slider/shortcode.php');
require_once('cwi-slider/metaboxes.php');




?>