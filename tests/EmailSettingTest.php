<?php
/**
 * Test for EmailSetting.
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;

/**
 * Test for EmailForm.
 *
 * @package AdapterGravityAddOn
 */
class EmailSettingTest extends TestCase {
	public function test_add_bottom_of_post_setting() {
		Functions\expect( '__' )
			->andReturnFirstArg();

		$this->assertEquals(
			[
				'form_options' => [
					'fields' => [
						[
							'name'  => 'aga_bottom_of_post',
							'type'  => 'toggle',
							'label' => 'Display at the bottom of every post',
						],
					],
				],
			],
			( new EmailSetting() )->add_bottom_of_post_setting( [] )
		);
	}
}
