<?php
/**
 * Plugin Name: Woocommerce SML (Software licence manager) Key generator
 * Description: Auto generate licence keys for WooCommerce orders using the Software licence manager plugin
 * Author:      Aaron Bowie (We are AG)
 * Author URI:  https://www.weareag.co.uk
 * Version:     1.0
 *
 * Big thanks to Omid Shamlu (http://wp-master.ir), I have updated the plugin to work with the latest WooCommerce (3.0+)
 */


 // Exit if accessed directly
 if (!defined('ABSPATH')) {
 	exit;
 }

if (!class_exists('WC_SLM')) {

  // Plugin path
  define('AG_WooSLM_DIR', plugin_dir_path(__FILE__));

  // SLM Credentials
  $api_url = str_replace(array('http://'), array('https://'), rtrim(get_option('woo_slm_api_url'), '/'));
  define('AG_WooSLM_API_URL', $api_url);
  define('AG_WooSLM_API_SECRET', get_option('woo_slm_api_secret'));

  // Include files and scripts
  require_once AG_WooSLM_DIR . 'include/create-licence.php';
  require_once AG_WooSLM_DIR . 'include/email-licence.php';
  if (is_admin()) {
    require_once AG_WooSLM_DIR . 'include/metaboxes.php';
    require_once AG_WooSLM_DIR . 'include/settings.php'; 
  }

}
