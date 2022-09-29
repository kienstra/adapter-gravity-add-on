<?php
/**
 * Email form, to optionally place at the end of a post.
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

use GFAPI;
use RGFormsModel;

/**
 * Handles the front-end display of the email form.
 *
 * Based on the settings, displays a form at the end of posts, and/or horizontally.
 * Also, adds classes to <input> elements of type 'text,' 'email,' and 'submit.'
 */
class EmailForm {

	/**
	 * Instance of EmailSetting.
	 *
	 * @var EmailSetting
	 */
	public $email_setting;

	/**
	 * Construct the class.
	 *
	 * @param EmailSetting $email_setting The email setting.
	 */
	public function __construct( EmailSetting $email_setting ) {
		$this->email_setting = $email_setting;
	}

	/**
	 * Add the filters for the class.
	 */
	public function init() {
		add_filter( 'the_content', [ $this, 'conditionally_append_form' ], 100 );
	}

	/**
	 * Conditionally append a form to the post content.
	 *
	 * The form returned from GFAPI::get_form( $form->id ) has more metadata.
	 * So it's not possible to simply pass $form to $this->do_append_form_to_content().
	 */
	public function conditionally_append_form( string $content ): string {
		$forms = RGFormsModel::get_forms();
		foreach ( $forms as $form ) {
			if ( isset( $form->id ) && $this->do_append_form_to_content( GFAPI::get_form( $form->id ) ) ) {
				return $this->append_form_to_content( $form->id, $content );
			}
		}

		return $content;
	}

	/**
	 * Whether to append a form to the content.
	 */
	public function do_append_form_to_content( array $form ): bool {
		return (
			isset( $form[ $this->email_setting->bottom_of_post ] )
			&&
			( '1' === $form[ $this->email_setting->bottom_of_post ] )
			&&
			is_single()
			&&
			( 'post' === get_post_type() )
		);
	}

	/**
	 * Append Gravity Form to the end of the post content.
	 *
	 * Filter callback for 'the_content.'
	 * Use the form that this class processed.
	 */
	public function append_form_to_content( int $form_id, string $content ): string {
		return $content . gravity_form( $form_id, false, false, false, '', true, 1, false );
	}
}
