<?php
/**
 * Class file for AGA_Setting
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

/**
 * Class AGA_Setting
 */
class AGA_Setting {

	/**
	 * Gravity Form settings
	 *
	 * @var array
	 */
	public $settings;

	/**
	 * Settings form
	 *
	 * @var object
	 */
	public $form;

	/**
	 * Setting name
	 *
	 * @var string
	 */
	public $setting_name;

	/**
	 * Description of settings
	 *
	 * @var string
	 */
	public $setting_description;

	/**
	 * Construct instance of class.
	 *
	 * @param array  $settings Associated with forms.
	 * @param object $form The form object that is shown.
	 */
	public function __construct( $settings, $form ) {
		$this->settings = $settings;
		$this->form = $form;
	}

	/**
	 * Store the name and description.
	 *
	 * @param array $setting_variables Gravity forms settings for forms.
	 * @return void
	 */
	public function set_values( $setting_variables ) {
		$this->setting_name = isset( $setting_variables['setting_name'] ) ? $setting_variables['setting_name'] : '';
		$this->setting_description = isset( $setting_variables['setting_description'] ) ? $setting_variables['setting_description'] : '';
		$this->add_setting();
	}

	/**
	 * Add the new setting to the existing settings.
	 *
	 * @return void.
	 */
	public function add_setting() {
		$checked_value = $this->checked_value( $this->setting_name, $this->form );
		$markup = '<tr>
						<th>
							<label for="' . esc_attr( $this->setting_name ) . '">' . esc_html( $this->setting_description ) . '</label>
						</th>
						<td>
							<input type="checkbox" value="1" checked="' . $checked_value . '" name="' . esc_attr( $this->setting_name ) . '">
						</td>
				  </tr>';
		$this->settings['Form Layout'][ $this->setting_name ] = $markup;
	}

	/**
	 * Get the settings object, but with different markup.
	 *
	 * @return array $settings With different markup for a specific setting.
	 */
	public function get_settings() {
		return $this->settings;
	}

	/**
	 * Get the attribute's value for whether the <input> is checked.
	 *
	 * To output in an <input type="checkbox"> element.
	 *
	 * @param string $setting_name Name of the setting.
	 * @param Object $form Gravity Form object.
	 * @return string $checked_attribute Attribute and value for whether the <input> is checked.
	 */
	public function checked_value( $setting_name, $form ) {
		return rgar( $form, $setting_name ) ? 'checked' : '';
	}

}
