<?php
/**
 * Email form factory.
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

use GFAPI;
use GFFormsModel;
use function gravity_form;

/**
 * Creates an email form class.
 */
class EmailFormFactory {
	public static function create( EmailSetting $email_setting ): EmailForm {
		return new EmailForm(
			$email_setting,
			GFFormsModel::get_forms(),
			static function( $form_id ) {
				return GFAPI::get_form( $form_id );
			},
			static function( ...$args ) {
				return gravity_form( ...$args );
			}
		);
	}
}
