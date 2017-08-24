<?php
/**
 * Class file for Layout_Setting
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

/**
 * Class Layout_Setting
 *
 * In the Gravity Form 'Form Settings' page, this adds checkboxes to the 'Form Layout' section.
 * Pass the name and description to the set_values() method.
 * And call get_settings() to get all of the markup for this 'Form Layout' section, including the new checkbox.
 */
class Layout_Setting {

	/**
	 * Gravity Form settings
	 *
	 * @var array
	 */
	public $settings;

	/**
	 * Settings form
	 *
	 * @var array
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
	 * @param array $settings Associated with forms.
	 * @param array $form The form that is shown.
	 */
	public function __construct( $settings, $form ) {
		$this->settings = $settings;
		$this->form = $form;
	}

	/**
	 * Store the name and description.
	 *
	 * These are needed to create the setting markup.
	 * Then, store this markup in the settings.
	 * Adding this markup to the settings is the main purpose of this class.
	 *
	 * @param string $name Gravity form name.
	 * @param string $description Gravity form description.
	 * @return void
	 */
	public function set_values( $name, $description ) {
		$this->setting_name = $name;
		$this->setting_description = $description;
		$this->add_setting();
	}

	/**
	 * Add the new setting to the existing settings.
	 *
	 * @return void
	 */
	public function add_setting() {
		$markup = '<tr>
						<th>
							<label for="' . esc_attr( $this->setting_name ) . '">' . esc_html( $this->setting_description ) . '</label>
						</th>
						<td>
							<input type="checkbox" value="1" ' . checked( $this->is_checked(), true, false ) . ' name="' . esc_attr( $this->setting_name ) . '">
						</td>
					</tr>';

		if ( isset( $this->settings['Form Layout'] ) && is_array( $this->settings['Form Layout'] ) ) {
			$this->settings['Form Layout'][ $this->setting_name ] = $markup;
		}
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
	 * Get whether the 'checked' attribute for the <input> should appear.
	 *
	 * @return boolean $is_checked Whether the checked attribute should appear as 'checked'.
	 */
	public function is_checked() {
		return ( isset( $this->form[ $this->setting_name ] ) && ( '1' === $this->form[ $this->setting_name ] ) );
	}

}
