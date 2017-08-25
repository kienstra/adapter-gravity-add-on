<?php
/**
 * Email settings for Adapter Gravity Add On
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

/**
 * Adds checkboxes to the 'Form Layout' section on the 'Form Settings' page.
 *
 * Uses Gravity Forms filters to add checkboxes to that settings section.
 * And adds checkboxes to display the form at the bottom of the page, and to display it horizontally.
 *
 * @see class Layout_Setting
 */
class Email_Setting {

	/**
	 * Instance of the add-on.
	 *
	 * @var object
	 */
	public $add_on;

	/**
	 * Bottom of post setting name.
	 *
	 * @var string
	 */
	public $bottom_of_post = 'aga_bottom_of_post';

	/**
	 * Horizontal display setting name.
	 *
	 * @var string
	 */
	public $horizontal_display = 'aga_horizontal_display';

	/**
	 * Instantiate the add-on.
	 *
	 * @param object $add_on Instance of the add-on.
	 */
	public function __construct( $add_on ) {
		$this->add_on = $add_on;
	}

	/**
	 * Add the filters for the class.
	 *
	 * @return void
	 */
	public function init() {
		add_filter( 'gform_form_settings', array( $this, 'get_bottom_of_post_setting' ), 10, 2 );
		add_filter( 'gform_form_settings', array( $this, 'get_horizontal_setting' ), 10, 2 );
		add_filter( 'gform_pre_form_settings_save', array( $this, 'save_settings' ) );
		add_filter( 'gform_enable_field_label_visibility_settings', '__return_true' );
	}

	/**
	 * Add a setting to display the form at the bottom of posts.
	 *
	 * In the 'Form Layout' section of the 'Form Settings' page.
	 * If this checkbox is checked, the form will display at the bottom of every post.
	 *
	 * @param array $settings Associated with forms.
	 * @param array $form The form that is shown.
	 * @return array $settings Now with options to place the label inline and at the bottom.
	 */
	public function get_bottom_of_post_setting( $settings, $form ) {
		$bottom_of_post_setting = new Layout_Setting( $settings, $form );
		$bottom_of_post_setting->set_values(
			$this->bottom_of_post,
			__( 'Display at the bottom of every single-post page', 'adapter-gravity-add-on' )
		);
		return $bottom_of_post_setting->get_settings();
	}

	/**
	 * Get the form settings, with a checkbox to display the form horizontally.
	 *
	 * In the 'Form Layout' section of the 'Form Settings' page.
	 * When checked, this checkbox adds a class to the form, which causes it to display horizontally.
	 *
	 * @param array $settings Associated with forms.
	 * @param array $form The form that is shown.
	 * @return array  $settings Now with options to place the label inline and at the bottom.
	 */
	public function get_horizontal_setting( $settings, $form ) {
		$horizontal_form_setting = new Layout_Setting( $settings, $form );
		$horizontal_form_setting->set_values(
			$this->horizontal_display,
			__( 'Display form horizontally', 'adapter-gravity-add-on' )
		);
		return $horizontal_form_setting->get_settings();
	}

	/**
	 * Save the new form settings.
	 *
	 * This saves the values of the new settings that this class adds.
	 * Including the settings to display horizontally, and at the bottom of the post.
	 * rgpost() is a Gravity Forms helper function.
	 *
	 * @param array $form The form that is shown.
	 * @return array $form With additional settings, or null if $form is not an array.
	 */
	public function save_settings( $form ) {
		if ( ! is_array( $form ) ) {
			return $form;
		}
		$form[ $this->bottom_of_post ] = rgpost( $this->bottom_of_post );
		$form[ $this->horizontal_display ] = rgpost( $this->horizontal_display );
		return $form;
	}

}
