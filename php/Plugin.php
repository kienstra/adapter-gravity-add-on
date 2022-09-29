<?php
/**
 * Plugin bootstrap file
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

/**
 * Registers this add-on via the Gravity Forms add-on framework.
 *
 * First, includes the add-on framework.
 * Then, includes and registers the add-on with it.
 * Most of the main plugin settings are actually in Adapter_Add_On.
 *
 * @see https://www.gravityhelp.com/documentation/article/gfaddon
 */
class Plugin {

	private $include_framework;
	private $register_add_on;

	/**
	 * Plugin constructor.
	 */
	public function __construct( $include_framework, $register_add_on ) {
		$this->include_framework = $include_framework;
		$this->register_add_on   = $register_add_on;
	}

	/**
	 * Load this add-on with the Gravity Forms add-on hook.
	 */
	public function init() {
		add_action( 'gform_loaded', [ $this, 'register' ], 5 );
	}

	/**
	 * Register the add-on, using strategy that Gravity Forms recommends.
	 *
	 * If the needed Gravity Forms method does not exist, display an admin error and return.
	 * Otherwise, require and register the main add-on file.
	 */
	public function register() {
		call_user_func( $this->include_framework );
		call_user_func( $this->register_add_on, __NAMESPACE__ . '\AdapterAddOn' );
	}
}
