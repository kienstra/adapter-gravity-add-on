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
		$checked_attribute = aga_get_gform_checked_attribute( $this->setting_name , $this->form );
		$this->settings['Form Layout'][ $this->setting_name ] = "
			<tr>
		<th><label for='{$this->setting_name}'>" . $this->setting_description . "</label></th>
		<td><input type='checkbox' value='1' {$checked_attribute} name='{$this->setting_name}'></td>
			</tr>\n";
		return $this->settings;
	}

}
