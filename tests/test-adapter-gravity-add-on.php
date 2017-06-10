<?php
/**
 * Test for main plugin file.
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

/**
 * Test for adapter-gravity-add-on.php
 *
 * @package AdapterGravityAddOn
 */
class Test_Adapter_Gravity_Add_On extends \WP_UnitTestCase {

	/**
	 * Setup.
	 *
	 * @inheritdoc
	 */
	public function setUp() {
		parent::setUp();
		include_once( dirname( __FILE__ ) . '/../adapter-gravity-add-on.php' );
	}

	/**
	 * Test that the Plugin class exists, and that get_instance() returns an instance of it.
	 *
	 * @see adapter-gravity-add-on.php
	 */
	public function test_add_on() {
		$this->assertTrue( class_exists( 'AdapterGravityAddOn\Plugin' ) );
		$this->assertEquals( 'AdapterGravityAddOn\Plugin', get_class( Plugin::get_instance() ) );
	}

}
