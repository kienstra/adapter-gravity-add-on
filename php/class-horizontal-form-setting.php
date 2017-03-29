<?php
/**
 * Class file for Horizontal_Form_Setting
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

class Horizontal_Form_Setting extends AGA_Setting {

	public static function get_settings( $settings, $form ) {
		parent::instantiate( $settings , $form );
		parent::set_variables( array(
			'setting_name' => 'aga_horizontal_display',
					'setting_description' => __( 'Display form horizontally' , 'adapter-gravity-add-on' ),
	 	) );
		return parent::$instance->settings_with_new_markup();
	}
}
