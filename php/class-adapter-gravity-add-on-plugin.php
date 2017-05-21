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
	 * Whether to enqueue this plugin's styling.
	 *
	 * @var boolean
	 */
	public $do_enqueue_plugin_styling_by_default = true;

	/**
	 * Plugin components.
	 *
	 * @var array
	 */
	public $components = array();

	/**
	 * Construct the class.
	 */
	public function __construct() {
		add_action( 'gform_loaded', array( $this, 'conditionally_include_and_instantiate' ) );
		add_action( 'plugins_loaded', array( $this, 'plugin_localization' ) );
		add_action( 'gform_enqueue_scripts', array( $this, 'conditionally_enqueue_styling' ) );
	}

	/**
	 * Conditionally include plugin files and instantiate the classes.
	 *
	 * Only do this if the dependency plugin Gravity Forms is activated.
	 * Check for the presence of classes GFAPI and RGFormsModel.
	 *
	 * @return void.
	 */
	public function conditionally_include_and_instantiate() {
		if ( class_exists( 'GFAPI' ) && class_exists( 'RGFormsModel' ) ) {
			$this->load_plugin_files();
			$this->instantiate_classes();
		}
	}

	/**
	 * Load the files for the plugin.
	 *
	 * @return void.
	 */
	public function load_plugin_files() {
		require_once __DIR__ . '/class-aga-form.php';
		require_once __DIR__ . '/class-aga-setting.php';
		require_once __DIR__ . '/class-gravity-settings.php';
		require_once __DIR__ . '/class-aga-controller.php';
	}

	/**
	 * Instantiate the plugin classes.
	 *
	 * @return void.
	 */
	public function instantiate_classes() {
		$this->components['gravity_settings'] = new Gravity_Settings();
		$this->components['aga_controller'] = new AGA_Controller();
	}

	/**
	 * Load the textdomain for the plugin, enabling translation.
	 *
	 * @return void.
	 */
	public function plugin_localization() {
		load_plugin_textdomain( $this->plugin_slug, false, $this->plugin_slug . '/languages' );
	}

	/**
	 * Enqueue this plugin's styling if the condition is true.
	 *
	 * @return void.
	 */
	public function conditionally_enqueue_styling() {

		/**
		 * Filter whether to enqueue this plugin's styling.
		 *
		 * @param boolean $do_enqueue Whether to enqueue styling.
		 */
		$do_enqueue = apply_filters( 'aga_do_enqueue_css' , $this->do_enqueue_plugin_styling_by_default );

		if ( $do_enqueue ) {
			wp_enqueue_style( $this->plugin_slug . '-gravity-style' , plugins_url( $this->plugin_slug . '/css/aga-gravity.css' ), array(), $this->plugin_version );
		}
	}

}
