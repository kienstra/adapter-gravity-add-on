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
	 * Instance of plugin.
	 *
	 * @var object
	 */
	public $add_on;

	/**
	 * Instance of tested class.
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
		$this->instance = $this->add_on->components['email_setting'];
	}

	/**
	 * Test construct().
	 *
	 * @see Email_Setting::__construct().
	 */
	public function test_construct() {
		$this->assertEquals( __NAMESPACE__ . '\Email_Setting', get_class( $this->instance ) );
	}
	/**
	 * Test init().
	 *
	 * @see Email_Setting::init().
	 */
	public function test_init() {
		$this->instance->init();
		$this->assertEquals( 'aga_bottom_of_post', $this->instance->bottom_of_post );
		$this->assertEquals( 'aga_horizontal_display', $this->instance->horizontal_display );
		$this->assertEquals( 10, has_filter( 'gform_form_settings', array( $this->instance, 'get_bottom_of_post_setting' ) ) );
		$this->assertEquals( 10, has_filter( 'gform_form_settings', array( $this->instance, 'get_horizontal_setting' ) ) );
		$this->assertEquals( 10, has_filter( 'gform_pre_form_settings_save', array( $this->instance, 'save_settings' ) ) );
	}

	/**
	 * Test test_get_bottom_of_post_setting().
	 *
	 * @see Email_Setting::get_bottom_of_post_setting().
	 */
	public function test_get_bottom_of_post_setting() {
		$layout_key = 'Form Layout';
		$initial_settings = array(
			$layout_key => array(),
		);
		$form = array(
			$this->instance->bottom_of_post => '1',
		);
		$actual_settings = $this->instance->get_bottom_of_post_setting( $initial_settings, $form );
		$markup = $actual_settings[ $layout_key ][ $this->instance->bottom_of_post ];

		$this->assertContains( $this->instance->bottom_of_post, $markup );
		$this->assertContains( 'Display at the bottom of every single-post page', $markup );
		$this->assertContains( 'checked=\'checked\'', $markup );

		$form_not_checked = array(
			$this->instance->bottom_of_post => '',
		);
		$actual_settings_not_checked = $this->instance->get_bottom_of_post_setting( $initial_settings, $form_not_checked );
		$markup_not_checked = $actual_settings_not_checked[ $layout_key ][ $this->instance->bottom_of_post ];
		$this->assertNotContains( 'checked=\'checked\'', $markup_not_checked );
	}

	/**
	 * Test test_get_horizontal_setting().
	 *
	 * @see Email_Setting::get_get_horizontal_setting().
	 */
	public function test_get_horizontal_setting() {
		$layout_key = 'Form Layout';
		$initial_settings = array(
			$layout_key => array(),
		);
		$form = array(
			$this->instance->horizontal_display => '1',
		);
		$actual_settings = $this->instance->get_horizontal_setting( $initial_settings, $form );
		$markup = $actual_settings[ $layout_key ][ $this->instance->horizontal_display ];

		$this->assertContains( $this->instance->horizontal_display, $markup );
		$this->assertContains( 'Display form horizontally', $markup );
		$this->assertContains( 'checked=\'checked\'', $markup );

		$form_not_checked = array(
			$this->instance->horizontal_display => '',
		);
		$actual_settings_not_checked = $this->instance->get_bottom_of_post_setting( $initial_settings, $form_not_checked );
		$markup_not_checked = $actual_settings_not_checked[ $layout_key ][ $this->instance->bottom_of_post ];
		$this->assertNotContains( 'checked=\'checked\'', $markup_not_checked );
	}

}
