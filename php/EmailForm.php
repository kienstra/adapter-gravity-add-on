<?php
/**
 * Email form, to optionally place at the end of a post.
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

use JetBrains\PhpStorm\Pure;
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
	 * Whether to use AJAX by default for the Gravity form.
	 *
	 * @var string
	 */
	public $use_ajax_by_default = true;

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
		add_filter( 'gform_pre_render', [ $this, 'conditionally_display_form_horizontally' ] );
		add_filter( 'the_content', [ $this, 'conditionally_append_form' ], 100 );
	}

	/**
	 * Conditionally append a form to the post content.
	 */
	public function conditionally_display_form_horizontally( array $form ): array {
		$horizontal_setting = $this->email_setting->horizontal_display;
		return isset( $form[ $horizontal_setting ] ) && '1' === $form[ $horizontal_setting ]
			? $this->add_horizontal_display( $form )
			: $form;
	}

	/**
	 * Add a class to display the form horizontally.
	 */
	public function add_horizontal_display( array $form ): array {
		$setting = 'cssClass';
		if ( ! isset( $form[ $this->css_class_setting ] ) ) {
			return $form;
		} elseif ( '' === $form[ $this->css_class_setting ] ) {
			$form[ $this->css_class_setting ] = $this->horizontal_class;
		} elseif ( false === strpos( $form[ $setting ], $this->horizontal_class ) ) {
			$form[ $this->css_class_setting ] = $form[ $this->css_class_setting ] . ' ' . $this->horizontal_class;
		}
		return $form;
	}

	/**
	 * Conditionally append a form to the post content.
	 *
	 * The form returned from \GFAPI::get_form( $form->id ) has more metadata.
	 * So it's not possible to simply pass $form to $this->do_append_form_to_content().
	 */
	public function conditionally_append_form( string $content ): string {
		$forms = RGFormsModel::get_forms();
		foreach ( $forms as $form ) {
			if ( isset( $form->id ) && $this->do_append_form_to_content( \GFAPI::get_form( $form->id ) ) ) {
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
		/**
		* Whether to use ajax in the Gravity Form at the bottom of a single post.
		*
		* @param boolean $do_ajax Whether to use ajax.
		*/
		$do_ajax = apply_filters( 'aga_use_ajax_in_form_at_bottom_of_single_post', $this->use_ajax_by_default );
		if ( ! is_bool( $do_ajax ) ) {
			$do_ajax = $this->use_ajax_by_default;
		}

		return $content . gravity_form( $form_id, false, false, false, '', $do_ajax, 1, false );
	}
}
