<?php
/*
 * Plugin Name: EMP for WordPress
 * Version: 1.0
 * Plugin URI: http://www.liaisonedu.com/
 * Description: Embed EMP forms into your WordPress pages.
 * Author: Barrett Cox
 * Author URI: http://www.barrettcox.com/
 * Requires at least: 4.0
 * Tested up to: 4.0
 *
 * Text Domain: empforwp
 * Domain Path: /lang/
 *
 * @package WordPress
 * @author Barrett Cox
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Includes
require_once( 'includes/empforwp_functions.php' );
require_once( 'includes/shortcodes.php' );
require_once( 'includes/custom_post_types.php' );
require_once( 'includes/custom_fields.php' );

/**
 * Returns the main instance of EMP_for_WordPress to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object EMP_for_WordPress
 */