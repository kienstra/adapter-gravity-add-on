<?php
/**
 * Class file for Adapter_Add_On
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

/**
 * Class Adapter_Add_On
 */
class Adapter_Add_On extends \GFAddOn {

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	protected $_version = '1.0.2';

	/**
	 * Minimum version of Gravity Forms allowed.
	 *
	 * @var string
	 */
	protected $_min_gravityforms_version = '1.9';

	/**
	 * Plugin slug.
	 *
	 * @var string
	 */
	protected $_slug = 'adapter-gravity-add-on';

	/**
	 * Path to the main add-on file.
	 *
	 * @var string
	 */
	protected $_path = 'adapter-gravity-add-on/php/class-adapter_add-on.php';

	/**
	 * Full path to add-on file.
	 *
	 * @var string
	 */
	protected $_full_path = __FILE__;

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	protected $_title = 'Adapter Gravity Add On';

	/**
	 * Short title.
	 *
	 * @var string
	 */
	protected $_short_title = 'Adapter Add On';

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
	 * Get the instance of this plugin
	 *
	 * @return object $instance Plugin instance.
	 */
	public static function get_instance() {
		static $instance;

		if ( ! $instance instanceof Adapter_Add_On ) {
			$instance = new Adapter_Add_On();
		}

		return $instance;
	}

	/**
	 * Call the parent init method, and add the plugin actions.
	 */
	public function init() {
		parent::init();
		$this->plugin_localization();
		$this->load_plugin_files();
		$this->instantiate_classes();
	}

	/**
	 * Load the files for the plugin.
	 *
	 * @return void
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
	 * @return void
	 */
	public function instantiate_classes() {
		$this->components['gravity_settings'] = new Gravity_Settings();
		$this->components['aga_controller'] = new AGA_Controller();
	}

	/**
	 * Load the textdomain for the plugin, enabling translation.
	 *
	 * @return void
	 */
	public function plugin_localization() {
		load_plugin_textdomain( $this->_slug, false, $this->_slug . '/languages' );
	}

	/**
	 * Get the stylesheets to enqueue.
	 *
	 * @return array $styles The stylesheets to enqueue..
	 */
	public function styles() {
		/**
		 * Filter whether to enqueue this plugin's styling.
		 *
		 * @param boolean $do_enqueue Whether to enqueue styling.
		 */
		$do_enqueue = apply_filters( 'aga_do_enqueue_css', $this->do_enqueue_plugin_styling_by_default );

		$styles = array(
			array(
				'handle'  => $this->_slug . '-gravity-style',
				'src'     => plugins_url( $this->_slug . '/css/aga-gravity.css' ),
				'version' => $this->_version,
				'enqueue' => $do_enqueue,
			),
		);
		return array_merge( parent::styles(), $styles );
	}

}
