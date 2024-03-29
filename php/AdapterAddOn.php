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
 * The properties in this override those defined in GFAddOn.
 * So their names are predetermined.
 *
 * @see https://www.gravityhelp.com/documentation/article/gfaddon
 */
class AdapterAddOn extends GFAddOn {
	public $_version                  = '1.0.2';
	public $_min_gravityforms_version = '1.9';
	public $_slug                     = 'adapter-gravity-add-on';
	public $_full_path                = __FILE__;

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
		$email_setting = EmailSettingFactory::create();
		$email_form    = EmailFormFactory::create( $email_setting );

		$email_setting->init();
		$email_form->init();
	}
}
