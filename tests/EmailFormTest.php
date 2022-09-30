<?php
/**
 * Test for EmailForm.
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

use PHPUnit\Framework\TestCase;
use stdClass;
use Brain\Monkey\Functions;
use Mockery;

/**
 * Test for EmailForm.
 *
 * @package AdapterGravityAddOn
 */
class EmailFormTest extends TestCase {

	use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

	private stdClass $email_setting;

	public function setUp(): void {
		parent::setUp();
		$this->email_setting = new stdClass();
	}

	public function test_init() {
		Functions\expect( 'add_filter' )
			->once();

		( new EmailForm(
			$this->email_setting,
			[],
			function () {},
			function () {}
		) )->init();
	}

	public function test_conditionally_append_form_no_form() {
		$this->assertEquals(
			'Example post content',
			( new EmailForm(
				$this->email_setting,
				[],
				function() {},
				function() {}
			) )->conditionally_append_form( 'Example post content' )
		);
	}

	public function test_conditionally_append_form_correct_form() {
		Functions\expect( 'get_post_type' )
			->andReturn( 'post' );

		$form                                = new stdClass();
		$form->id                            = '35';
		$this->email_setting->bottom_of_post = 'aga_bottom_of_post';
		$this->assertEquals(
			'Example post content This is the form',
			( new EmailForm(
				$this->email_setting,
				[ $form ],
				function() {
					return [ $this->email_setting->bottom_of_post => '1' ];
				},
				function() {
					return ' This is the form';
				}
			) )->conditionally_append_form( 'Example post content' )
		);
	}
}
