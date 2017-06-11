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
class Test_Plugin extends \WP_UnitTestCase {

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
		include_once( dirname( __FILE__ ) . '/../../../gravityforms/gravityforms.php' );
		include_once( dirname( __FILE__ ) . '/../../adapter-gravity-add-on.php' );
		$this->instance = Plugin::get_instance();
	}

	/**
	 * Test get_instance().
	 *
	 * @see Plugin::get_instance().
	 */
	public function test_get_instance() {
		$this->assertEquals( 'AdapterGravityAddOn\Plugin', get_class( $this->instance ) );
	}

	/**
	 * Test construct().
	 *
	 * @see Plugin::__construct().
	 */
	public function test_construct() {
		$this->assertEquals( 5, has_action( 'gform_loaded', array( $this->instance, 'register' ) ) );
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

}
