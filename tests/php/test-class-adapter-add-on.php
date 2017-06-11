<?php
/**
 * Tests for class Class_Adapter_Add_On.
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

/**
 * Tests for class Adapter_Add_Onn.
 *
 * @package AdapterGravityAddOn
 */
class Test_Class_Adapter_Add_On extends \WP_UnitTestCase {

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
	 * Test construct().
	 *
	 * @see Adapter_Add_Onn::__construct().
	 */
	public function test_construct() {
		$this->assertEquals( 5, has_action( 'gform_loaded', array( $this->instance, 'register' ) ) );
	}

}
