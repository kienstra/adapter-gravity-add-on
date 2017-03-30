<?php
/**
 * Extra settings for Adapter Gravity Add On
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

class Gravity_Settings {

	/**
	 * Construct the class.
	 */
	public function __construct() {
		add_filter( 'gform_form_settings', array( $this, 'gform_add_label_option' ), 1, 2 );
		add_filter( 'gform_form_settings', array( $this, 'gform_add_settings' ), 10, 2 );
		add_filter( 'gform_pre_form_settings_save' , array( $this, 'save_aga_settings' ) );
	}

	// Option to output label as placeholder.
	function gform_add_label_option( $settings, $form ) {
		$form_label_placement = isset( $settings['Form Layout']['form_label_placement'] ) ? $settings['Form Layout']['form_label_placement'] : '';
		if ( $form_label_placement ) {
			$settings = $this->add_label_placement_to_settings( $form_label_placement , $settings , $form );
		}
		return $settings;
	}

	function add_label_placement_to_settings( $form_label_placement, $settings, $form ) {
		$new_option = $this->get_option_for_in_placeholder( $form );
		$new_form_label_settings = $this->get_new_form_label_settings( $new_option , $form_label_placement );
		$settings = $this->add_new_form_label_settings_to_settings( $settings , $new_form_label_settings );
		return $settings;
	}

	function get_option_for_in_placeholder( $form ) {
		$option_name = 'in_placeholder';
		$selected_attribute = $this->get_placeholder_selected_attribute( $form );
		return '<option value="' . esc_attr( $option_name ) . '" ' . $selected_attribute . '>'
							. esc_html__( 'In placeholder', 'adapter-gravity-add-on' )
						. '</option>';
	}

	function get_new_form_label_settings( $new_option, $form_label_placement ) {
		$closing_select_tag = '</select>';
		$new_option_with_closing_select_tag = $new_option . ' ' . $closing_select_tag;
		$new_settings = str_replace( $closing_select_tag , $new_option_with_closing_select_tag , $form_label_placement );
		return $new_settings;
	}

	function add_new_form_label_settings_to_settings( $settings, $new_form_label_settings ) {
		$settings['Form Layout']['form_label_placement'] = $new_form_label_settings;
		return $settings;
	}

	function get_placeholder_selected_attribute( $form ) {
		$is_selected = ( ( isset( $form['labelPlacement'] ) ) && ( 'in_placeholder' == $form['labelPlacement'] ) );
		$selected_attribute = selected( $is_selected , 1 , false );
		return $selected_attribute;
	}

	// Option to echo form at bottom of post and to display it inline
	function gform_add_settings( $settings, $form ) {
		$settings_with_bottom_of_post_option = Bottom_Of_Post_Setting::get_settings( $settings , $form );
		$settings_with_inline_and_bottom_of_post_options = Horizontal_Form_Setting::get_settings( $settings_with_bottom_of_post_option , $form );
		return $settings_with_inline_and_bottom_of_post_options;
	}

	function save_aga_settings( $form ) {
		$form['aga_bottom_of_post'] = rgpost( 'aga_bottom_of_post' );
		$form['aga_horizontal_display'] = rgpost( 'aga_horizontal_display' );
		return $form;
	}

}

