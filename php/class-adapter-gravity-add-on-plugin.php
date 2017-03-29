<?php
/**
 * Class file for Adapter_Gravity_Add_On_Plugin
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

/**
 * Class Adapter_Gravity_Add_On_Plugin
 */
class Adapter_Gravity_Add_On_Plugin {

	/**
	 * Plugin slug.
	 *
	 * @var string
	 */
	public $plugin_slug = 'adapter-gravity-add-on';

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	public $plugin_version = '1.0.1';

	/**
	 * Construct the class.
	 */
	public function __construct() {
		add_action( 'plugins_loaded' , array( $this, 'conditionally_include_and_instantiate' ) );
		add_action( 'plugins_loaded' , array( $this, 'plugin_localization' ) );
		add_action( 'gform_enqueue_scripts' , array( $this, 'conditionally_enqueue_scripts' ) );
	}

	/**
	 * Conditionally include plugin files and instantiate the classes.
	 *
	 * Only do this if the dependency plugin Gravity Forms is activated.
	 * Check for the presence of the class GFAPI.
	 *
	 * @return void.
	 */
	public function conditionally_include_and_instantiate() {
		if ( class_exists( 'GFAPI' ) ) {
			$this->include_plugin_files();
		}
	}

	/**
	 * Load the textdomain for the plugin, enabling translation.
	 *
	 * @return void.
	 */
	public function include_plugin_files() {
		$included_files = array(
			'class-aga-form',
			'class-aga-setting',
			'aga-gravity-settings',
			'aga-controller',
		) ;
		foreach ( $included_files as $file ) {
			require_once __DIR__ . '/' . $file . '.php';
		}
	}

	/**
	 * Load the textdomain for the plugin, enabling translation.
	 *
	 * @return void.
	 */
	public function plugin_localization() {
		load_plugin_textdomain( $this->plugin_slug, false, $this->plugin_slug . '/languages' );
	}

	function conditionally_enqueue_scripts() {
		$do_enqueue = apply_filters( 'aga_do_enqueue_css' , true );
		if ( $do_enqueue ) {
			wp_enqueue_style( $this->plugin_slug . '-gravity-style' , plugins_url( '/css/aga-gravity.css' , __FILE__ ) , array() , $this->plugin_version );
		}
	}

}
