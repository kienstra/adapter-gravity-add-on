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

	/**
	 * Test set_values().
	 *
	 * @see Layout_Setting::set_values().
	 */
	public function test_set_values() {
		$layout_key = 'Form Layout';
		$name = 'Example Name';
		$initial_settings = array(
			$layout_key => array(),
		);
		$form = array(
			$name => '1',
		);

		$description = 'Baz description';
		$this->instance = new Layout_Setting( $initial_settings, $form );
		$this->instance->set_values( $name, $description );
		$this->assertEquals( $name, $this->instance->setting_name );
		$this->assertEquals( $description, $this->instance->setting_description );

		$markup = $this->instance->settings[ $layout_key ][ $name ];
		$this->assertContains( $name, $markup );
		$this->assertContains( $description, $markup );
	}

	/**
	 * Test get_settings().
	 *
	 * @see Layout_Setting::get_settings().
	 */
	public function test_get_settings() {
		$name = 'Example Name';
		$layout_key = 'Form Layout';
		$initial_settings = array(
			$layout_key => array(),
		);
		$form = array(
			$name => '1',
		);
		$this->instance = new Layout_Setting( $initial_settings, $form );
		$this->assertEquals( $this->instance->settings, $this->instance->get_settings() );
	}

	/**
	 * Test is_checked().
	 *
	 * @see Layout_Setting::is_checked().
	 */
	public function test_is_checked() {
		$settings = array();
		$name = 'Example Name';
		$description = 'Bar Description';
		$form = array(
			$name => '1',
		);
		$this->instance = new Layout_Setting( $settings, $form );
		$this->instance->set_values( $name, $description );
		$this->assertTrue( $this->instance->is_checked() );

		// When the value of the $name key is not '1', this should not be checked.
		$form_not_checked = array(
			$name => '',
		);
		$this->instance = new Layout_Setting( $settings, $form_not_checked );
		$this->instance->set_values( $name, $description );
		$this->assertFalse( $this->instance->is_checked() );
	}

}
