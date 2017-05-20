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
	 * @var int
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

	/**
	 * Add a Gravity form and create a new instance with it.
	 *
	 * @param int $form_id Gravity Form ID.
	 * @return void.
	 */
	public static function add_form( $form_id ) {
		if ( null === self::$instance ) {
			self::$instance = new self( $form_id );
		}
	}

	/**
	 * Instantiate the class.
	 *
	 * @param int $form_id Gravity Form ID.
	 */
	public function __construct( $form_id ) {
		$this->set_variables( $form_id );
	}

	/**
	 * Set form variables.
	 *
	 * @param int $form_id Gravity Form ID.
	 * @return void.
	 */
	private function set_variables( $form_id ) {
		$this->set_form_id( $form_id );
		$this->set_gform_object();
		$this->set_form_title();
		$this->set_ajax_option();
		$this->set_shortcode_string();
	}

	/**
	 * Set form ID as a property.
	 *
	 * @param int $form_id Gravity Form ID.
	 * @return void.
	 */
	private function set_form_id( $form_id ) {
		if ( ! isset( $this->form_id ) ) {
			$this->form_id = $form_id;
		}
	}

	/**
	 * Set Gravity Form object as a property.
	 *
	 * @return void.
	 */
	private function set_gform_object() {
		$this->gform_object = \GFAPI::get_form( $this->form_id );
	}

	/**
	 * Set the Gravity Form title.
	 *
	 * @return void.
	 */
	private function set_form_title() {
		$this->form_title = isset( $this->gform_object['title'] ) ? $this->gform_object['title'] : '';
	}

	/**
	 * Set the option of whether to use ajax in the form.
	 *
	 * @return void.
	 */
	private function set_ajax_option() {

		/**
		* Whether to use ajax in the Gravity Form at the bottom of a single post.
		*
		* @param boolean $do_ajax Whether to use ajax.
		*/
		$do_ajax = apply_filters( 'aga_use_ajax_in_form_at_bottom_of_single_post' , $this->do_use_ajax_by_default );

		if ( ( true === $do_ajax ) || ( 'true' === $do_ajax ) ) {
			$this->do_ajax = 'true';
		} else {
			$this->do_ajax = 'false';
		}
	}

	/**
	 * Set Gravity Form object as a property.
	 *
	 * @return void.
	 */
	private function set_shortcode_string() {
		$this->shortcode_string = '[gravityform id="' . esc_attr( $this->form_id ) . '" name="' . esc_attr( $this->form_title ) . '" title="false" description="false" ajax="' . esc_attr( $this->do_ajax ) . '"]';
	}

	/**
	 * Append Gravity Form to the end of the post content.
	 *
	 * Filter callback for 'the_content.'
	 * Use the form that this class processed.
	 *
	 * @param string $content Post content to filter.
	 * @return string $content Filtered post content markup.
	 */
	public static function append_form_to_content( $content ) {
		$form_markup = do_shortcode( self::$instance->shortcode_string );
		return $content . $form_markup;
	}

}
