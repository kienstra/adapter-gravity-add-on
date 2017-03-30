<?php
/**
 * Class file for Bottom_Of_Post_Setting
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

/**
 * Class Bottom_Of_Post_Setting
 */
class Bottom_Of_Post_Setting extends AGA_Setting {

	public static function get_settings( $settings, $form ) {
		parent::instantiate( $settings , $form );
		parent::set_variables( array(
			'setting_name' => 'aga_bottom_of_post',
				'setting_description' => __( 'Display at the bottom of every single-post page' , 'adapter-gravity-add-on' ),
		));
		return parent::$instance->settings_with_new_markup();
	}
}
