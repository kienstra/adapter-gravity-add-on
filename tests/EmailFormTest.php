<?php
/**
 * Test for EmailForm.
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

use Brain\Monkey\Functions;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

/**
 * Test for EmailForm.
 *
 * @package AdapterGravityAddOn
 */
class EmailFormTest extends TestCase {
	use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

	public function test_init() {
		Functions\expect( 'add_filter' )
			->once();

		( new EmailForm(
			new EmailSetting(),
			[],
			function () {},
			function () {}
		) )->init();
	}

	public function test_conditionally_append_form_no_form() {
		$this->assertEquals(
			'Example post content',
			( new EmailForm(
				new EmailSetting(),
				[],
				function () {},
				function () {}
			) )->conditionally_append_form( 'Example post content' )
		);
	}

	public function test_conditionally_append_form_correct_form() {
		Functions\expect( 'get_post_type' )
			->andReturn( 'post' );

		$email_setting                 = new EmailSetting();
		$form                          = new stdClass();
		$form->id                      = '35';
		$email_setting->bottom_of_post = 'aga_bottom_of_post';
		$this->assertEquals(
			'Example post content This is the form',
			( new EmailForm(
				$email_setting,
				[ $form ],
				function () {
					return [ $email_setting->bottom_of_post => '1' ];
				},
				function () {
					return ' This is the form';
				}
			) )->conditionally_append_form( 'Example post content' )
		);
	}
}
