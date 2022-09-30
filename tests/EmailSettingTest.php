<?php
/**
 * Test for EmailSetting.
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

use Mockery;
use Brain\Monkey\Functions;
use PHPUnit\Framework\TestCase;

/**
 * Test for EmailForm.
 *
 * @package AdapterGravityAddOn
 */
class EmailSettingTest extends TestCase {

	use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

	public function test_get_bottom_of_post_setting() {
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
			( new EmailSetting(
			) )->get_bottom_of_post_setting( [] )
		);
	}
}
