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
	 * Test register().
	 *
	 * @see Plugin::register().
	 */
	public function test_register() {
		$this->assertTrue( class_exists( 'AdapterGravityAddOn\Adapter_Add_On' ) );
		$this->assertTrue( class_exists( '\GFAddOn' ) );
		$this->assertTrue( in_array( 'AdapterGravityAddOn\Adapter_Add_On', \GFAddOn::get_registered_addons(), true ) );
	}

}
