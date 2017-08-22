<?php
/**
 * Email settings for Adapter Gravity Add On
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

/**
 * Class Email_Setting
 */
class Email_Setting {

	/**
	 * Instance of the plugin.
	 *
	 * @var object
	 */
	public $plugin;

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
	 * Instantiate the class.
	 *
	 * @param object $plugin Instance of the plugin.
	 */
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Add the filters for the class.
	 *
	 * @return void
	 */
	public function init() {
		add_filter( 'gform_form_settings', array( $this, 'get_bottom_of_post_setting' ), 10, 2 );
		add_filter( 'gform_form_settings', array( $this, 'get_horizontal_setting' ), 10, 2 );
		add_filter( 'gform_pre_form_settings_save', array( $this, 'save_settings' ) );
	}

	/**
	 * Add a setting to display the form at the bottom of posts.
	 *
	 * @param array $settings Associated with forms.
	 * @param array $form The form that is shown.
	 * @return array $settings Now with options to place the label inline and at the bottom.
	 */
	public function get_bottom_of_post_setting( $settings, $form ) {
		$bottom_of_post_setting = new Layout_Setting( $settings, $form );
		$bottom_of_post_setting->set_values(
			$this->bottom_of_post,
			__( 'Display at the bottom of every single-post page', 'adapter-gravity-add-on' )
		);
		return $bottom_of_post_setting->get_settings();
	}

	/**
	 * Get the form settings, with additional options.
	 *
	 * @param array $settings Associated with forms.
	 * @param array $form The form that is shown.
	 * @return array  $settings Now with options to place the label inline and at the bottom.
	 */
	public function get_horizontal_setting( $settings, $form ) {
		$horizontal_form_setting = new Layout_Setting( $settings, $form );
		$horizontal_form_setting->set_values(
			$this->horizontal_display,
			__( 'Display form horizontally', 'adapter-gravity-add-on' )
		);
		return $horizontal_form_setting->get_settings();
	}

	/**
	 * Get the form settings, with additional options.
	 *
	 * @param array $form The form that is shown.
	 * @return array $form With additional settings.
	 */
	public function save_settings( $form ) {
		$form[ $this->bottom_of_post ] = rgpost( $this->bottom_of_post );
		$form[ $this->horizontal_display ] = rgpost( $this->horizontal_display );
		return $form;
	}

}
