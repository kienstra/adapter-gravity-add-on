<?php
/**
 * Tests for class Class_Adapter_Add_On.
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

// Include test class in order to load dependency file in Test_Adapter_Gravity_Add_On::setUp().
require_once dirname( __FILE__ ) . '/../AdapterGravityAddOnTest.php';

/**
 * Tests for class Adapter_Add_On.
 *
 * @package AdapterGravityAddOn
 */
class AdapterAddOnTest extends AdapterGravityAddOnTest {

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
	 * @see Adapter_Add_On::get_instance().
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
		$this->assertEquals( true, $this->instance->do_enqueue_add_on_styling_by_default );
	}

	/**
	 * Test pre_init().
	 *
	 * @see Adapter_Add_On::pre_init().
	 */
	public function test_pre_init() {
		$this->assertEquals( 'Adapter Gravity Add On', $this->instance->_title );
		$this->assertEquals( 'Adapter Add On', $this->instance->_short_title );
	}

	/**
	 * Test textdomain().
	 *
	 * @see Adapter_Add_On::textdomain().
	 */
	public function test_textdomain() {
		$this->instance->init();
		$this->assertEquals( 10, has_action( 'init', [ $this->instance, 'textdomain' ] ) );
	}

	/**
	 * Test load_add_on_files().
	 *
	 * @see Adapter_Add_On::load_add_on_files().
	 */
	public function test_load_add_on_files() {
		$this->instance->load_add_on_files();
		$this->assertTrue( class_exists( 'AdapterGravityAddOn\Layout_Setting' ) );
		$this->assertTrue( class_exists( 'AdapterGravityAddOn\Email_Setting' ) );
		$this->assertTrue( class_exists( 'AdapterGravityAddOn\Email_Form' ) );
	}

	/**
	 * Test instantiate_classes().
	 *
	 * @see Adapter_Add_On::instantiate_classes().
	 */
	public function test_instantiate_classes() {
		$this->assertEquals( 'AdapterGravityAddOn\Email_Setting', get_class( $this->instance->components['email_setting'] ) );
		$this->assertEquals( 'AdapterGravityAddOn\Email_Form', get_class( $this->instance->components['email_form'] ) );
	}

	/**
	 * Test styles().
	 *
	 * @see Adapter_Add_On::styles().
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
	 * @see Adapter_Add_On::do_enqueue().
	 */
	public function test_do_enqueue() {
		$this->assertTrue( $this->instance->do_enqueue() );
		add_filter( 'aga_do_enqueue_css', '__return_false' );
		$this->assertFalse( $this->instance->do_enqueue() );
	}

}
