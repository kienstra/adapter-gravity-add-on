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
	 * Instance of this plugin.
	 *
	 * @var object
	 */
	public $instance;

	/**
	 * Setup.
	 *
	 * @inheritdoc
	 */
	public function setUp() {
		parent::setUp();

		$dependency_file = dirname( __FILE__ ) . '/../../gravityforms/gravityforms.php';
		$dependency_name = __( 'Gravity Forms', 'adapter-gravity-add-on' );

		if ( ! file_exists( $dependency_file ) ) {
			$this->markTestSkipped( sprintf(
				__( 'Cannot test the Adapter Gravity Add On because the %s dependency is not present.', 'adapter-gravity-add-on' ),
				$dependency_name
			) );
			return;
		} else {
			include_once( $dependency_file );
		}

		$this->instance = Plugin::get_instance();
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
