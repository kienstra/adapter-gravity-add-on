<?php
/**
 * Tests for class Email_Setting.
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
class Test_Class_Email_Setting extends Test_Adapter_Gravity_Add_On {

	/**
	 * Setup.
	 *
	 * @inheritdoc
	 */
	public function setUp() {
		parent::setUp();
		$this->instance = new Email_Setting();
	}

	/**
	 * Test construct().
	 *
	 * @see Email_Setting::__construct().
	 */
	public function test_construct() {
		$this->assertEquals( 'aga_bottom_of_post', $this->instance->bottom_of_post );
		$this->assertEquals( 10, has_filter( 'gform_form_settings', array( $this->instance, 'add_bottom_of_post_setting' ) ) );
		$this->assertEquals( 10, has_filter( 'gform_form_settings', array( $this->instance, 'add_horizontal_setting' ) ) );
		$this->assertEquals( 10, has_filter( 'gform_pre_form_settings_save', array( $this->instance, 'save_settings' ) ) );
	}

}
