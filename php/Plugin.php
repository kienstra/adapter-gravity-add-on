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

	/**
	 * Minimum version of Gravity Forms allowed.
	 *
	 * @var string
	 */
	private string $min_gravityforms_version = '1.9';

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
	 *
	 * @return void|null
	 */
	public function register() {
		if ( ! method_exists( 'GFForms', 'include_addon_framework' ) ) {
			add_action( 'admin_notices', [ $this, 'gravity_not_installed' ] );
			return;
		}

		\GFForms::include_addon_framework();
		require_once dirname( __FILE__ ) . '/class-adapter-add-on.php';
		\GFAddOn::register( __NAMESPACE__ . '\Adapter_Add_On' );
	}

	/**
	 * Admin error message if Gravity Forms does not appear to be installed.
	 *
	 * @return void
	 */
	public function gravity_not_installed() {
		?>
		<div class="error">
			<?php /* translators: %s: minimum Gravity Forms version */ ?>
			<p><?php printf( esc_html__( 'In order to use the Adapter Gravity Add-On plugin, please install and activate Gravity Forms %s or higher.', 'adapter-gravity-add-on' ), esc_html( $this->min_gravityforms_version ) ); ?></p>
		</div>
		<?php
	}
}
