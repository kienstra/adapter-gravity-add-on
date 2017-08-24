<?php
/**
 * Class file for Adapter_Add_On
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

/**
 * Main add-on class.
 *
 * Mainly follows the add-on conventions from the Gravity Form documentation.
 * The properties in this override those defined in \GFAddOn.
 * So their names are predetermined.
 *
 * @see https://www.gravityhelp.com/documentation/article/gfaddon
 */
class Adapter_Add_On extends \GFAddOn {

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	public $_version = '1.0.2';

	/**
	 * Minimum version of Gravity Forms allowed.
	 *
	 * @var string
	 */
	public $_min_gravityforms_version = '1.9';

	/**
	 * Plugin slug.
	 *
	 * @var string
	 */
	public $_slug = 'adapter-gravity-add-on';

	/**
	 * Path to the main add-on file.
	 *
	 * @var string
	 */
	public $_path = 'adapter-gravity-add-on/php/class-adapter_add-on.php';

	/**
	 * Full path to add-on file.
	 *
	 * @var string
	 */
	public $_full_path = __FILE__;

	/**
	 * Plugin title.
	 *
	 * @var string
	 */
	public $_title;

	/**
	 * Short title.
	 *
	 * @var string
	 */
	public $_short_title;

	/**
	 * Whether to enqueue this plugin's styling.
	 *
	 * @var boolean
	 */
	public $do_enqueue_plugin_styling_by_default = true;

	/**
	 * Add-on components.
	 *
	 * @var array
	 */
	public $components = array();

	/**
	 * Statically get the instance of this add-on.
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
	 * Assign properties during the class constructor.
	 *
	 * It's not possible to assign these in the same lines as the property declarations.
	 * These are expressions.
	 *
	 * @return void
	 */
	public function pre_init() {
		parent::pre_init();
		$this->_title = __( 'Adapter Gravity Add On', 'adapter-gravity-add-on' );
		$this->_short_title = __( 'Adapter Add On', 'adapter-gravity-add-on' );
	}

	/**
	 * Call the parent init method, and add the plugin actions.
	 *
	 * @return void
	 */
	public function init() {
		parent::init();
		$this->load_plugin_files();
		$this->instantiate_classes();
		add_action( 'init', array( $this, 'plugin_textdomain' ) );
	}

	/**
	 * Load the textdomain for the plugin, enabling translation.
	 *
	 * @action init
	 * @return void
	 */
	public function plugin_textdomain() {
		load_plugin_textdomain( $this->_slug, false, $this->_slug . '/languages' );
	}

	/**
	 * Load the files for the plugin.
	 *
	 * @return void
	 */
	public function load_plugin_files() {
		require_once __DIR__ . '/class-layout-setting.php';
		require_once __DIR__ . '/class-email-setting.php';
		require_once __DIR__ . '/class-email-form.php';
	}

	/**
	 * Instantiate the plugin classes.
	 *
	 * @return void
	 */
	public function instantiate_classes() {
		$this->components['email_setting'] = new Email_Setting( $this );
		$this->components['email_form'] = new Email_Form( $this );
		$this->components['email_setting']->init();
		$this->components['email_form']->init();
	}

	/**
	 * Get the stylesheets to enqueue.
	 *
	 * @return array $styles The stylesheets to enqueue..
	 */
	public function styles() {
		$styles = array(
			array(
				'handle'  => $this->_slug . '-gravity-style',
				'src'     => plugins_url( $this->_slug . '/css/aga-gravity.css' ),
				'version' => $this->_version,
				'enqueue' => array(
					array( $this, 'do_enqueue' ),
				),
			),
		);

		return array_merge( parent::styles(), $styles );
	}

	/**
	 * Whether to enqueue this addon's styling.
	 *
	 * @return boolean $do_enqueue Whether to enqueue this addon's styling.
	 */
	public function do_enqueue() {

		/**
		 * Filter whether to enqueue this plugin's styling.
		 *
		 * @param boolean $do_enqueue Whether to enqueue styling.
		 */
		$do_enqueue = apply_filters( 'aga_do_enqueue_css', $this->do_enqueue_plugin_styling_by_default );
		return ( $do_enqueue && ( ! is_admin() ) );
	}

}
