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
 */
class EmailSetting {

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
	 * Add the filters for the class.
	 *
	 * @return void
	 */
	public function init() {
		add_filter( 'gform_form_settings_fields', [ $this, 'get_bottom_of_post_setting' ] );
		add_filter( 'gform_form_settings_fields', [ $this, 'get_horizontal_setting' ] );
	}

	/**
	 * Add a setting to display the form at the bottom of posts.
	 *
	 * In the 'Form Layout' section of the 'Form Settings' page.
	 * If this checkbox is checked, the form will display at the bottom of every post.
	 *
	 * @param array $fields Associated with forms.
	 * @return array $fields Now with options to place the label inline and at the bottom.
	 */
	public function get_bottom_of_post_setting( $fields ) {
		$fields['form_options']['fields'][] = [
			'name'  => $this->bottom_of_post,
			'type'  => 'toggle',
			'label' => __( 'Display at the bottom of every single-post page', 'adapter-gravity-add-on' ),
		];

		return $fields;
	}

	/**
	 * Get the form settings, with a checkbox to display the form horizontally.
	 *
	 * In the 'Form Layout' section of the 'Form Settings' page.
	 * When checked, this checkbox adds a class to the form, which causes it to display horizontally.
	 *
	 * @param array $fields Associated with forms.
	 * @return array  $fields Now with options to place the label inline and at the bottom.
	 */
	public function get_horizontal_setting( $fields ) {
		$fields['form_options']['fields'][] = [
			'name'  => $this->horizontal_display,
			'type'  => 'toggle',
			'label' => __( 'Display form horizontally', 'adapter-gravity-add-on' ),
		];

		return $fields;
	}
}
