<?php
 /*
   Plugin Name: BinaryMLM
   Plugin URI: https://www.letscms.com/
   description: Binary MLM is a plugin which helps to manage binary networks within the WordPress CMS. Binary MLM Software is suitable for all of MLM organizations irrespective of their sizes.
   Version: 2.0
   Author: LetsCMS
   License: GPL
   */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Define MLM_PLUGIN_FILE.
if ( ! defined( 'MLM_PLUGIN_FILE' ) ) {
	define( 'MLM_PLUGIN_FILE', __FILE__ );
}

// Define MLM_URL.
if ( ! defined( 'MLM_URL' ) ) {
	define( 'MLM_URL', plugins_url( '', __FILE__ ) );
}

if ( ! defined( 'Letscms_MLM_URL' ) ) {
  define( 'Letscms_MLM_URL', 'https://www.letscms.com/' );
}

if (!defined('MLM_VERSION_KEY')) {
    define('MLM_VERSION_KEY', 'myplugin_version');
 }

// Define MLM_VERSION
if (!defined('MLM_VERSION_NUM')) {
    define('MLM_VERSION_NUM', '2.0');
}

add_option(MLM_VERSION_KEY, MLM_VERSION_NUM);

// Include the main Letscms_mlmclass.
if ( ! class_exists( 'Letscms_mlm' ) ) {
  include_once dirname( __FILE__ ) . '/includes/mlmfunction.php';
	include_once dirname( __FILE__ ) . '/includes/class-mlm.php';

}


do_action( 'mlmplugins_loaded' );

/**
 * Main instance of Letscms_mlm class.
 *
 * Returns the main instance of Binary MLM to prevent the need to use globals.
 *
 * @since  1.0
 * @return LetsCMS
 */
function letscms_mlm() {
	return Letscms_mlm::instance();
}

// Global for backwards compatibility.
$GLOBALS['binarymlm'] = letscms_mlm();
