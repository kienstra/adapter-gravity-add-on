<?php
/**
 * Class file for Adapter_Add_On
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

use GFAddOn;

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
class AdapterAddOn extends GFAddOn {

	public string $_version                           = '1.0.2';
	public string $_min_gravityforms_version          = '1.9';
	public string $_slug                              = 'adapter-gravity-add-on';
	public string $_full_path                         = __FILE__;
	public bool $do_enqueue_add_on_styling_by_default = true;
	public EmailForm $email_form;
	public EmailSetting $email_setting;

	/**
	 * Assigns the add-on properties.
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
	 * Statically gets the instance of this add-on.
	 */
	public static function get_instance(): AdapterAddOn {
		static $instance;

		if ( ! $instance instanceof AdapterAddOn ) {
			$instance = new AdapterAddOn();
		}

		return $instance;
	}

	/**
	 * Call the parent init method, and add the plugin actions.
	 */
	public function init() {
		$this->instantiate_classes();
		parent::init();
	}

	/**
-	 * Instantiate the add-on classes.
-	 */
	public function instantiate_classes() {
		$email_setting = new EmailSetting();
		$email_form    = new EmailForm( $email_setting );

		$email_setting->init();
		$email_form->init();
	}

	/**
	 * Get the stylesheet to enqueue.
	 *
	 * Follows the convention for add-ons in the Gravity Forms documentation.
	 * Uses this class's method do_enqueue() as a callback.
	 */
	public function styles(): array {
		return array_merge(
			parent::styles(),
			[
				[
					'handle'  => $this->_slug . '-gravity-style',
					'src'     => plugins_url( $this->_slug . '/css/aga-gravity.css' ),
					'version' => $this->_version,
					'enqueue' => [
						[ $this, 'do_enqueue' ],
					],
				],
			]
		);
	}

	/**
	 * Whether to enqueue this add-on's styling.
	 *
	 * @return boolean $do_enqueue Whether to enqueue this addon's styling.
	 */
	public function do_enqueue(): bool {
		/**
		 * Filter whether to enqueue this add-on's styling.
		 *
		 * @param boolean $do_enqueue Whether to enqueue styling.
		 */
		return apply_filters( 'aga_do_enqueue_css', ! is_admin() );
	}

}
