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
	 * @param object $add_on Instance of the plugin.
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
	}

	/**
	 * Add a setting to display the form at the bottom of posts.
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
	 * Get the form settings, with additional options.
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
	 * Get the form settings, with additional options.
	 *
	 * This saves the values of the new settings that this class adds.
	 * rgpost() is a Gravity Forms helper function.
	 *
	 * @param array $form The form that is shown.
	 * @return array $form With additional settings.
	 */
	public function save_settings( $form ) {
		$form[ $this->bottom_of_post ] = rgpost( $this->bottom_of_post );
		$form[ $this->horizontal_display ] = rgpost( $this->horizontal_display );
		return $form;
	}

}
