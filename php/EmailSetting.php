<?php
/**
 * Email settings for Adapter Gravity Add On
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

/**
 * Adds checkboxes to the 'Form Options' section on the 'Form Settings' page.
 *
 * Uses Gravity Forms filters to add checkboxes to that settings section.
 * And adds checkboxes to display the form at the bottom of the page.
 */
class EmailSetting {
	private string $bottom_of_post = 'aga_bottom_of_post';

	/**
	 * Add the filters for the class.
	 */
	public function init() {
		add_filter( 'gform_form_settings_fields', [ $this, 'get_bottom_of_post_setting' ] );
	}

	/**
	 * Add a setting to display the form at the bottom of posts.
	 *
	 * In the 'Form Layout' section of the 'Form Settings' page.
	 * If this checkbox is checked, the form will display at the bottom of every post.
	 */
	public function get_bottom_of_post_setting( array $fields ): array {
		$fields['form_options']['fields'][] = [
			'name'  => $this->bottom_of_post,
			'type'  => 'toggle',
			'label' => __( 'Display at the bottom of every post', 'adapter-gravity-add-on' ),
		];

		return $fields;
	}
}
