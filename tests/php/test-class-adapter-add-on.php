<?php
/**
 * Tests for class Class_Adapter_Add_On.
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
class Test_Class_Adapter_Add_On extends Test_Adapter_Gravity_Add_On {

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
		do_action( 'gform_loaded' );
		$this->instance = Adapter_Add_On::get_instance();
	}

	/**
	 * Test get_instance().
	 *
	 * @see Adapter_Add_Onn::get_instance().
	 */
	public function test_get_instance() {
		$this->assertEquals( 'AdapterGravityAddOn\Adapter_Add_On', get_class( Adapter_Add_On::get_instance() ) );
		$this->assertEquals( $this->instance, Adapter_Add_On::get_instance() );
		$this->assertInternalType( 'string', $this->instance->_version );
		$this->assertInternalType( 'string', $this->instance->_min_gravityforms_version );
		$this->assertInternalType( 'string', $this->instance->_slug );
		$this->assertInternalType( 'string', $this->instance->_path );
		$this->assertInternalType( 'string', $this->instance->_full_path );
		$this->assertInternalType( 'string', $this->instance->_title );
		$this->assertInternalType( 'string', $this->instance->_short_title );
		$this->assertEquals( true, $this->instance->do_enqueue_plugin_styling_by_default );
	}

	/**
	 * Test pre_init().
	 *
	 * @see Adapter_Add_Onn::pre_init().
	 */
	public function test_pre_init() {
		$this->assertEquals( 'Adapter Gravity Add On', $this->instance->_title );
		$this->assertEquals( 'Adapter Add On', $this->instance->_short_title );
	}

	/**
	 * Test plugin_textdomain().
	 *
	 * @see Adapter_Add_Onn::plugin_textdomain().
	 */
	public function test_plugin_textdomain() {
		$this->instance->init();
		$this->assertEquals( 10, has_action( 'init', array( $this->instance, 'plugin_textdomain' ) ) );
	}

	/**
	 * Test load_plugin_files().
	 *
	 * @see Adapter_Add_Onn::load_plugin_files().
	 */
	public function test_load_plugin_files() {
		$this->instance->load_plugin_files();
		$this->assertTrue( class_exists( 'AdapterGravityAddOn\Layout_Setting' ) );
		$this->assertTrue( class_exists( 'AdapterGravityAddOn\Gravity_Setting' ) );
		$this->assertTrue( class_exists( 'AdapterGravityAddOn\Email_Form' ) );
	}

	/**
	 * Test instantiate_classes().
	 *
	 * @see Adapter_Add_Onn::instantiate_classes().
	 */
	public function test_instantiate_classes() {
		$this->assertEquals( 'AdapterGravityAddOn\Gravity_Setting', get_class( $this->instance->components['gravity_setting'] ) );
		$this->assertEquals( 'AdapterGravityAddOn\Email_Form', get_class( $this->instance->components['email_form'] ) );
	}

	/**
	 * Test styles().
	 *
	 * @see Adapter_Add_Onn::styles().
	 */
	public function test_styles() {
		$style = $this->instance->styles()[2];
		$this->assertTrue( in_array( $this->instance->_slug . '-gravity-style', $style, true ) );
		$this->assertTrue( in_array( plugins_url( $this->instance->_slug . '/css/aga-gravity.css' ), $style, true ) );
		$this->assertTrue( in_array( $this->instance->_version, $style, true ) );
		$this->assertEquals( 'AdapterGravityAddOn\Adapter_Add_On', get_class( $style['enqueue'][0][0] ) );
		$this->assertEquals( 'do_enqueue', $style['enqueue'][0][1] );
	}

	/**
	 * Test do_enqueue().
	 *
	 * @see Adapter_Add_Onn::do_enqueue().
	 */
	public function test_do_enqueue() {
		$this->assertTrue( $this->instance->do_enqueue() );
		add_filter( 'aga_do_enqueue_css', '__return_false' );
		$this->assertFalse( $this->instance->do_enqueue() );
	}

}
