<?php
/**
 * Main plugin file.
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

use GFAddOn;
use GFForms;

/*
Plugin Name: Adapter Gravity Add-On
Plugin URI: https://github.com/kienstra/adapter-gravity-add-on
Description: Add-on for Gravity Forms, with an option to show a form at the end of every post. To use, click "Forms" in the left menu of your admin screen. Scroll over one of your forms, and click "Settings." Scroll down to "Form Options." You'll see a toggle "Display at the bottom of every post."
Version: 1.0.3
Author: Ryan Kienstra
Author URI: https://getlocalci.com
License: GPL2+
*/

require_once dirname( __FILE__ ) . '/vendor/autoload.php';

( new Plugin(
	function() {
		GFForms::include_addon_framework();
	},
	function( $add_on ) {
		GFAddOn::register( $add_on );
	}
) )->init();
