<?php
/**
 * Plugin bootstrap file.
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

/*
Plugin Name: Adapter Gravity Add-On
Plugin URI: www.ryankienstra.com/adapter-gravity-add-on
Description: Add-on for Gravity Forms, with options for inline display, placeholders, and showing at the end of every post. To use, click "Forms" in the left menu of your admin screen. Scroll over one of your forms, and click "Form Settings." Scroll down to "Form Layout." You'll see a new option for "Label placement": "In placeholder." You'll also see options to "Display at the bottom of every single-post page" and "Display form horizontally."

Version: 1.0.1
Author: Ryan Kienstra
Author URI: www.ryankienstra.com
License: GPL2
*/

/**
 * Register the plugin as an add-on if the Gravity Form method exists.
 *
 * @return void.
 */
function register() {
	if ( method_exists( 'GFForms', 'include_addon_framework' ) ) {
		\GFForms::include_addon_framework();
		require_once dirname( __FILE__ ) . '/php/class-adapter-add-on.php';
		\GFAddOn::register( 'AdapterGravityAddOn\Adapter_Add_On' );
	}
}
add_action( 'gform_loaded', 'AdapterGravityAddOn\register', 5 );
