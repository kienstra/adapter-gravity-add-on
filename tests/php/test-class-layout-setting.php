<?php
/**
 * Tests for class Layout_Setting.
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

// Include test class in order to load dependency file in Test_Adapter_Gravity_Add_On::setUp().
include_once( dirname( __FILE__ ) . '/../test-adapter-gravity-add-on.php' );

/**
 * Tests for class Adapter_Add_Onn.
 *
 * @package AdapterGravityAddOn
 */
class Test_Class_Layout_Setting extends Test_Adapter_Gravity_Add_On {

	/**
	 * Instance of plugin.
	 *
	 * @var object
	 */
	public $add_on;

	/**
	 * Instance of Layout_Setting.
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
		$this->add_on = Adapter_Add_On::get_instance();
	}

	/**
	 * Test construct().
	 *
	 * @see Layout_Setting::__construct().
	 */
	public function test_construct() {
		$settings = array(
			'foo_key' => 'foo_value',
		);
		$form = array(
			'bar_form_field' => 'bar_form_value',
		);
		$this->instance = new Layout_Setting( $settings, $form );
		$this->assertEquals( $this->instance->settings, $settings );
		$this->assertEquals( $this->instance->form, $form );
	}

}
