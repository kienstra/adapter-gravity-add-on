<?php
/**
 * Extra settings for Adapter Gravity Add On
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

/**
 * Class Gravity_Setting
 */
class Gravity_Setting {

	/**
	 * Plugin version.
	 *
	 * @var string
	 */
	public $bottom_of_post = 'aga_bottom_of_post';

	/**
	 * Add the filters for the class.
	 */
	public function __construct() {
		add_filter( 'gform_form_settings', array( $this, 'add_bottom_of_post_setting' ), 10, 2 );
		add_filter( 'gform_form_settings', array( $this, 'add_horizontal_setting' ), 10, 2 );
		add_filter( 'gform_pre_form_settings_save', array( $this, 'save_settings' ) );
	}

	/**
	 * Add a setting to display the form at the bottom of posts.
	 *
	 * @param array  $settings Associated with forms.
	 * @param object $form The form object that is shown.
	 * @return array  $settings Now with options to place the label inline and at the bottom.
	 */
	public function add_bottom_of_post_setting( $settings, $form ) {
		$bottom_of_post_setting = new Layout_Setting( $settings, $form );
		$bottom_of_post_setting->set_values(
			$this->bottom_of_post,
			__( 'Display at the bottom of every single-post page', 'adapter-gravity-add-on' )
		);
		return $bottom_of_post_setting->get_settings();
	}

	/**
	 * Get the form settings, with additional options.
	 *
	 * @param array  $settings Associated with forms.
	 * @param object $form The form object that is shown.
	 * @return array  $settings Now with options to place the label inline and at the bottom.
	 */
	public function add_horizontal_setting( $settings, $form ) {
		$horizontal_form_setting = new Layout_Setting( $settings, $form );
		$horizontal_form_setting->set_values(
			'aga_horizontal_display',
			__( 'Display form horizontally', 'adapter-gravity-add-on' )
		);
		return $horizontal_form_setting->get_settings();
	}

	/**
	 * Get the form settings, with additional options.
	 *
	 * @param array $form The form that is shown.
	 * @return array $form With additional settings.
	 */
	public function save_settings( $form ) {
		$form[ $this->bottom_of_post ] = rgpost( $this->bottom_of_post );
		$form['aga_horizontal_display'] = rgpost( 'aga_horizontal_display' );
		return $form;
	}

}
