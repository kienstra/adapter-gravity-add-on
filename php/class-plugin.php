<?php
/**
 * Plugin bootstrap file
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

/**
 * Class Plugin
 */
class Plugin {

	/**
	 * Get the instance of this plugin
	 *
	 * @return object $instance Plugin instance.
	 */
	public static function get_instance() {
		static $instance;

		if ( ! $instance instanceof Plugin ) {
			$instance = new Plugin();
		}

		return $instance;
	}

	/**
	 * Load this add-on with the Gravity Forms add-on hook.
	 */
	private function __construct() {
		add_action( 'gform_loaded', array( $this, 'register' ), 5 );
	}

	/**
	 * Register the plugin as an add-on if the Gravity Form method exists.
	 *
	 * @return void
	 */
	public function register() {
		\GFForms::include_addon_framework();
		require_once dirname( __FILE__ ) . '/class-adapter-add-on.php';
		\GFAddOn::register( 'AdapterGravityAddOn\Adapter_Add_On' );
	}

}
