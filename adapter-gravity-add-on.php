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

require_once dirname( __FILE__ ) . '/php/class-adapter-add-on.php';

global $adapter_gravity_add_on_plugin;
$adapter_gravity_add_on_plugin = new Adapter_Gravity_Add_On_Plugin();
