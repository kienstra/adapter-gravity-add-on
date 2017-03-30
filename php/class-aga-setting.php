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
	 * Instance of this class
	 *
	 * @var object
	 */
	protected static $instance;

	/**
	 * Gravity Form settings
	 *
	 * @var array
	 */
	protected $settings;

	/**
	 * Settings form
	 *
	 * @var object
	 */
	protected $form;

	/**
	 * Setting name
	 *
	 * @var string
	 */
	protected $setting_name;

	/**
	 * Description of settings
	 *
	 * @var string
	 */
	protected $setting_description;

	/**
	 * Construct instance of class.
	 *
	 * @param array  $settings Associated with forms.
	 * @param object $form The form object that is shown.
	 */
	private function __construct( $settings, $form ) {
		$this->settings = $settings;
		$this->form = $form;
	}

	/**
	 * Call constructor of class.
	 *
	 * @param array  $settings Associated with forms.
	 * @param object $form The form object that is shown.
	 * @return void
	 */
	public static function instantiate( $settings, $form ) {
		self::$instance = new self( $settings, $form );
	}

	/**
	 * Store settings in instance.
	 *
	 * @param array $setting_variables Gravity forms settings for forms.
	 * @return void
	 */
	public static function set_variables( $setting_variables ) {
		self::$instance->setting_name = isset( $setting_variables['setting_name'] ) ? $setting_variables['setting_name'] : '';
		self::$instance->setting_description = isset( $setting_variables['setting_description'] ) ? $setting_variables['setting_description'] : '';
	}

	/**
	 * Get the settings object, but with different markup.
	 *
	 * @return array $settings With different markup for a specific setting.
	 */
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
