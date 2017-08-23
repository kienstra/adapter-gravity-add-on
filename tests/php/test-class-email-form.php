<?php
/**
 * Tests for class Email_Form.
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
class Test_Class_Email_Form extends Test_Adapter_Gravity_Add_On {

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
		$this->instance = $this->add_on->components['email_form'];
	}

	/**
	 * Test construct().
	 *
	 * @see Email_Form::__construct().
	 */
	public function test_construct() {
		$this->assertEquals( __NAMESPACE__ . '\Email_Form', get_class( $this->instance ) );
		$this->assertEquals( __NAMESPACE__ . '\Adapter_Add_On', get_class( $this->instance->add_on ) );
		$this->assertEquals( 'gform-inline', $this->instance->horizontal_class );
		$this->assertEquals( 'cssClass', $this->instance->css_class_setting );
		$this->assertEquals( 'form-control', $this->instance->default_class_of_input );
		$this->assertEquals( 'btn btn-primary btn-med', $this->instance->default_submit_button_classes );
		$this->assertTrue( $this->instance->use_ajax_by_default );
	}

	/**
	 * Test init().
	 *
	 * @see Email_Form::init().
	 */
	public function test_init() {
		$this->instance->init();
		$this->assertEquals( 10, has_filter( 'gform_pre_render', array( $this->instance, 'conditionally_display_form_horizontally' ) ) );
		$this->assertEquals( 100, has_filter( 'the_content', array( $this->instance, 'conditionally_append_form' ) ) );
		$this->assertEquals( 12, has_filter( 'gform_field_content', array( $this->instance, 'set_class_of_input_tags' ) ) );
		$this->assertEquals( 10, has_filter( 'gform_submit_button', array( $this->instance, 'submit_button' ) ) );
	}

	/**
	 * Test conditionally_display_form_horizontally().
	 *
	 * @see Email_Form::conditionally_display_form_horizontally().
	 */
	public function test_conditionally_display_form_horizontally() {
		$horizontal_setting = $this->add_on->components['email_setting']->horizontal_display;
		$form = array(
			$horizontal_setting => '1',
			$this->instance->css_class_setting => '',
		);
		$actual_form = $this->instance->conditionally_display_form_horizontally( $form );
		$this->assertEquals( $this->instance->horizontal_class, $actual_form[ $this->instance->css_class_setting ] );

		$form_no_horizontal_display = array(
			$horizontal_setting => '',
			$this->instance->css_class_setting => '',
		);
		$actual_form = $this->instance->conditionally_display_form_horizontally( $form_no_horizontal_display );
		$this->assertEquals( '', $actual_form[ $this->instance->css_class_setting ] );

		$pre_existing_class = 'foo-class';
		$form_with_pre_existing_class = array(
			$horizontal_setting => '1',
			$this->instance->css_class_setting => $pre_existing_class,
		);
		$actual_form = $this->instance->conditionally_display_form_horizontally( $form_with_pre_existing_class );
		$this->assertEquals( $pre_existing_class . ' ' . $this->instance->horizontal_class, $actual_form[ $this->instance->css_class_setting ] );

		$form_with_pre_existing_class_not_horizontal = array(
			$horizontal_setting => '',
			$this->instance->css_class_setting => $pre_existing_class,
		);
		$actual_form = $this->instance->conditionally_display_form_horizontally( $form_with_pre_existing_class_not_horizontal );
		$this->assertEquals( $pre_existing_class, $actual_form[ $this->instance->css_class_setting ] );
	}

	/**
	 * Test add_horizontal_display).
	 *
	 * @see Email_Form::add_horizontal_display().
	 */
	public function test_add_horizontal_display() {
		$form = array(
			$this->instance->css_class_setting => '',
		);
		$actual_form = $this->instance->add_horizontal_display( $form );
		$this->assertEquals( $this->instance->horizontal_class, $actual_form[ $this->instance->css_class_setting ] );

		$classes = 'example-class bar-class';
		$form_with_classes = array(
			$this->instance->css_class_setting => $classes,
		);
		$actual_form = $this->instance->add_horizontal_display( $form_with_classes );
		$expected_classes = $classes . ' ' . $this->instance->horizontal_class;
		$this->assertEquals( $expected_classes, $actual_form[ $this->instance->css_class_setting ] );
	}

	/**
	 * Test conditionally_append_form).
	 *
	 * @see Email_Form::conditionally_append_form().
	 */
	public function test_conditionally_append_form() {
		global $wp_query, $post;
		$gf_upgrade = \gf_upgrade();
		$gf_upgrade->install();

		$title = 'A New Test Form Title';
		$new_form_id = \RGFormsModel::insert_form( $title );
		$description = 'Example description';
		$form_meta = array(
			'id'                   => $new_form_id,
			'description'          => $description,
			'labelPlacement'       => 'top_label',
			'descriptionPlacement' => 'below',
			'button'               => array(
				'type'                 => 'text',
				'text'                 => 'Submit',
				'imageUrl'             => '',
			),
			'fields'               => array(),
		);
		\RGFormsModel::update_form_meta( $new_form_id, $form_meta );
		$initial_content = 'Example content';
		$actual_content = $this->instance->conditionally_append_form( $initial_content );
		$this->assertEquals( $initial_content, $actual_content );

		$new_form_meta = array_merge(
			$form_meta,
			array(
				$this->add_on->components['email_setting']->bottom_of_post => '1',
			)
		);
		\RGFormsModel::update_form_meta( $new_form_id, $new_form_meta );
		$actual_content = $this->instance->conditionally_append_form( $initial_content );
		$this->assertEquals( $initial_content, $actual_content );

		// @codingStandardsIgnoreStart
		$wp_query->is_single = true;
		$post = $this->factory()->post->create( array( 'post_type' => 'post' ) );
		// @codingStandardsIgnoreEnd
		$actual_content = $this->instance->conditionally_append_form( $initial_content );
		// This should have the form appended to the content.
		$this->assertContains( $initial_content, $actual_content );
		$this->assertContains( 'gform_wrapper_' . $new_form_id , $actual_content );
		$this->assertNotContains( $title, $actual_content );
		$this->assertNotContains( $description, $actual_content );
		$this->assertContains( 'gform_ajax', $actual_content );
	}

}
