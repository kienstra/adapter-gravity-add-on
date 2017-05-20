<?php
/**
 * Extra settings for Adapter Gravity Add On
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

/**
 * Class Gravity_Settings
 */
class Gravity_Settings {

	/**
	 * Add the filters for the class.
	 */
	public function __construct() {
		add_filter( 'gform_form_settings', array( $this, 'gform_add_settings' ), 10, 2 );
		add_filter( 'gform_pre_form_settings_save', array( $this, 'save_aga_settings' ) );
	}

	/**
	 * Get the form settings, with additional options.
	 *
	 * @param array  $settings Associated with forms.
	 * @param object $form The form object that is shown.
	 * @return array  $settings Now with options to place the label inline and at the bottom.
	 */
	function gform_add_settings( $settings, $form ) {
		$settings_with_bottom_of_post_option = Bottom_Of_Post_Setting::get_settings( $settings, $form );
		return Horizontal_Form_Setting::get_settings( $settings_with_bottom_of_post_option, $form );
	}

	/**
	 * Get the form settings, with additional options.
	 *
	 * @param object $form The form object that is shown.
	 * @return object $form With additional settings.
	 */
	function save_aga_settings( $form ) {
		$form['aga_bottom_of_post'] = rgpost( 'aga_bottom_of_post' );
		$form['aga_horizontal_display'] = rgpost( 'aga_horizontal_display' );
		return $form;
	}

}
