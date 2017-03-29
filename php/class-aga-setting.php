<?php
/**
 * Class file for AGA_Setting
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

class AGA_Setting {

	protected static $instance;
	protected $settings;
	protected $form;
	protected $setting_name;
	protected $setting_description;

	private function __construct( $settings, $form ) {
		$this->settings = $settings;
		$this->form = $form;
	}

	public static function instantiate( $settings, $form ) {
		self::$instance = new self( $settings , $form );
	}

	public static function set_variables( $setting_variables ) {
		self::$instance->setting_name = isset( $setting_variables['setting_name'] ) ? $setting_variables['setting_name'] : '';
		self::$instance->setting_description = isset( $setting_variables['setting_description'] ) ? $setting_variables['setting_description'] : '';
	}

	public function settings_with_new_markup() {
		$checked_attribute = $this->get_gform_checked_attribute( $this->setting_name, $this->form );
		$markup = '<tr>
						<th>
							<label for="' . esc_attr( $this->setting_name ) . '">' . esc_html( $this->setting_description ) . '</label>
						</th>
						<td>
							<input type="checkbox" value="1" ' . $checked_attribute . ' name="' . esc_attr( $this->setting_name ) . '">
						</td>
				  </tr>';
		$this->settings['Form Layout'][ $this->setting_name ] = $markup;
		return $this->settings;
	}

	/**
	 * Get the attribute and value for whether the form is checked.
	 *
	 * To output in an <input type="checkbox"> element.
	 *
	 * @param string $setting_name Name of the setting.
	 * @param Object $form Gravity Form object.
	 * @return string $checked_attribute Attribute and value for whether the <input> is checked.
	 */
	public function get_gform_checked_attribute( $setting_name, $form ) {
		$is_checked = rgar( $form, $setting_name );
		return checked( $is_checked , '1' , false );
	}

}
