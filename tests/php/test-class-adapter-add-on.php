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
	 * Test construct().
	 *
	 * @see Adapter_Add_Onn::__construct().
	 */
	public function test_construct() {
		$this->assertInternalType( 'string', $this->instance->_version );
		$this->assertInternalType( 'string', $this->instance->_min_gravityforms_version );
		$this->assertInternalType( 'string', $this->instance->_slug );
		$this->assertInternalType( 'string', $this->instance->_path );
		$this->assertInternalType( 'string', $this->instance->_full_path );
		$this->assertInternalType( 'string', $this->instance->_title );
		$this->assertInternalType( 'string', $this->instance->_short_title );
		$this->assertEquals( true, $this->instance->do_enqueue_plugin_styling_by_default );
	}

}
