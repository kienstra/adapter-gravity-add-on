<?php
/**
 * Tests for class Plugin.
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

/**
 * Tests for class Plugin.
 *
 * @package AdapterGravityAddOn
 */
class PluginTest extends Test_Adapter_Gravity_Add_On {

	/**
	 * Instance of Plugin.
	 *
	 * @var object.
	 */
	public $instance;

	/**
	 * Setup.
	 *
	 * @inheritdoc
	 */
	public function setUp() {
		parent::setUp();
		$this->instance = Plugin::get_instance();
	}

	/**
	 * Test get_instance().
	 *
	 * @see Plugin::get_instance().
	 */
	public function test_get_instance() {
		$this->assertEquals( 'AdapterGravityAddOn\Plugin', get_class( Plugin::get_instance() ) );
		$this->assertEquals( $this->instance, Plugin::get_instance() );
	}

	/**
	 * Test __construct().
	 *
	 * @see Plugin::__construct().
	 */
	public function test_construct() {
		$this->assertEquals( 5, has_action( 'gform_loaded', [ $this->instance, 'register' ] ) );
		$this->assertEquals( '1.9', $this->instance->_min_gravityforms_version );
	}

	/**
	 * Test register().
	 *
	 * @see Plugin::register().
	 */
	public function test_register() {
		$this->instance->register();
		$this->assertTrue( class_exists( 'AdapterGravityAddOn\Adapter_Add_On' ) );
		$this->assertTrue( class_exists( '\GFAddOn' ) );
		$this->assertTrue( in_array( 'AdapterGravityAddOn\Adapter_Add_On', \GFAddOn::get_registered_addons(), true ) );
	}

	/**
	 * Test gravity_not_installed().
	 *
	 * @see Plugin::gravity_not_installed().
	 */
	public function test_gravity_not_installed() {
		ob_start();
		$this->instance->gravity_not_installed();
		$buffer = ob_get_clean();
		$this->assertContains( 'In order to use the Adapter Gravity Add-On plugin', $buffer );
		$this->assertContains( $this->instance->_min_gravityforms_version, $buffer );
	}
}
