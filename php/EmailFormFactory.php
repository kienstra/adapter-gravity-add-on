<?php
/**
 * Email form, to optionally place at the end of a post.
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

use GFAPI;
use GFFormsModel;
use function gravity_form;

/**
 * Handles the front-end display of the email form.
 *
 * Based on the settings, displays a form at the end of posts, and/or horizontally.
 * Also, adds classes to <input> elements of type 'text,' 'email,' and 'submit.'
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
				gravity_form( ...$args );
			}
		);
	}
}
