<?php
/**
 * Class file for AGA_Form
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

/**
 * Class AGA_Form
 */
class AGA_Form {

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * This form's ID.
	 *
	 * @var
	 */
	private $form_id;

	/**
	 * Gravity Form object
	 *
	 * @var object
	 */
	private $gform_object;

	/**
	 * Form title
	 *
	 * @var string
	 */
	private $form_title;

	/**
	 * Whether to use ajax
	 *
	 * @var boolean
	 */
	private $do_ajax;

	/**
	 * Whether to use ajax by default
	 *
	 * @var boolean
	 */
	private $do_use_ajax_by_default = true;

	/**
	 * Shortcode string
	 *
	 * @var string
	 */
	private $shortcode_string;

	public static function add_form( $form_id ) {
		if ( null == self::$instance ) {
			self::$instance = new self( $form_id );
		}
	}

	private function __construct( $form_id ) {
		$this->set_variables( $form_id );
	}

	private function set_variables( $form_id ) {
		$this->set_form_id( $form_id );
		$this->set_gform_object();
		$this->set_form_title();
		$this->set_ajax_option();
		$this->set_shortcode_string();
	}

	private function set_form_id( $form_id ) {
		if ( ! isset( $this->form_id ) ) {
			$this->form_id = $form_id;
		}
	}

	private function set_gform_object() {
		$this->gform_object = \GFAPI::get_form( $this->form_id );
	}

	private function set_form_title() {
		$this->form_title = isset( $this->gform_object['title'] ) ? $this->gform_object['title'] : '';
	}

	private function set_ajax_option() {

		/**
		* Whether to use ajax in the Gravity Form at the bottom of a single post.
		*
		* @param boolean $do_ajax Whether to use ajax.
		*/
		$do_ajax = apply_filters( 'aga_use_ajax_in_form_at_bottom_of_single_post' , $this->$do_use_ajax_by_default );

		if ( ( true == $do_ajax ) || ( 'true' == $do_ajax ) ) {
			$this->do_ajax = 'true';
		} else {
			$this->do_ajax = 'false';
		}
	}

	private function set_shortcode_string() {
		$this->shortcode_string = '[gravityform id="' . esc_attr( $this->form_id ) . '" name="' . esc_attr( $this->form_title ) . '" title="false" description="false" ajax="' . esc_attr( $this->do_ajax ) . '"]';
	}

	public static function append_form_to_content( $content ) {
		$form_markup = do_shortcode( self::$instance->shortcode_string );
		return $content . $form_markup;
	}
}
