<?php
/**
 * Class file for Adapter_Add_On
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

// phpcs:disable PSR2.Classes.PropertyDeclaration.Underscore

/**
 * Add-on class.
 *
 * Mainly follows the add-on conventions from the Gravity Forms documentation.
 * The properties in this override those defined in \GFAddOn.
 * So their names are predetermined.
 *
 * @see https://www.gravityhelp.com/documentation/article/gfaddon
 */
class AdapterAddOn extends \GFAddOn {

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
	 * Add-on slug.
	 *
	 * @var string
	 */
	public $_slug = 'adapter-gravity-add-on';

	/**
	 * Path to the main add-on file.
	 *
	 * @var string
	 */
	public $_path;

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
	 * Whether to enqueue this add-on's styling.
	 *
	 * @var boolean
	 */
	public $do_enqueue_add_on_styling_by_default = true;

	/**
	 * Add-on components.
	 *
	 * @var array
	 */
	public $components = [];

	/**
	 * Assign add-on properties.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->_path        = $this->_slug . '/php/AdapterAddOn.php';
		$this->_title       = __( 'Adapter Gravity Add On', 'adapter-gravity-add-on' );
		$this->_short_title = __( 'Adapter Add On', 'adapter-gravity-add-on' );

		parent::__construct();
	}

	/**
	 * Call the parent init method, and add the plugin actions.
	 *
	 * @return void
	 */
	public function init() {
		parent::init();
		$this->instantiate_classes();
	}

	/**
	 * Instantiate the add-on classes.
	 *
	 * @return void
	 */
	public function instantiate_classes() {
		$this->components['email_setting'] = new EmailSetting( $this );
		$this->components['email_form']    = new EmailForm( $this );
		$this->components['email_setting']->init();
		$this->components['email_form']->init();
	}

	/**
	 * Get the stylesheet to enqueue.
	 *
	 * Follows the convention for add-ons in the Gravity Forms documentation.
	 * Uses this class's method do_enqueue() as a callback.
	 *
	 * @return array $styles The stylesheets to enqueue..
	 */
	public function styles() {
		$styles = [
			[
				'handle'  => $this->_slug . '-gravity-style',
				'src'     => plugins_url( $this->_slug . '/css/aga-gravity.css' ),
				'version' => $this->_version,
				'enqueue' => [
					[ $this, 'do_enqueue' ],
				],
			],
		];

		return array_merge( parent::styles(), $styles );
	}

	/**
	 * Whether to enqueue this add-on's styling.
	 *
	 * @return boolean $do_enqueue Whether to enqueue this addon's styling.
	 */
	public function do_enqueue() {
		/**
		 * Filter whether to enqueue this add-on's styling.
		 *
		 * @param boolean $do_enqueue Whether to enqueue styling.
		 */
		return apply_filters( 'aga_do_enqueue_css', ! is_admin() );
	}

}
