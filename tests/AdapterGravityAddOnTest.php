<?php
/**
 * Test for main plugin file.
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

use PHPUnit\Framework\TestCase;

/**
 * Test for adapter-gravity-add-on.php
 *
 * @package AdapterGravityAddOn
 */
class AdapterGravityAddOnTest extends TestCase {

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
	public function setUp(): void {
		parent::setUp();

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
