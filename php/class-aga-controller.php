<?php
/**
 * Controller for Adapter Gravity Add On
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

/**
 * Class AGA_Controller
 */
class AGA_Controller {

	/**
	 * Default class of the input tags.
	 *
	 * @var string
	 */
	public $default_class_of_input = 'form-control';

	/**
	 * Construct the class.
	 */
	public function __construct() {
		add_action( 'template_redirect', array( $this, 'search_for_form_to_display_at_end_of_post' ) );
		add_filter( 'gform_field_content', array( $this, 'set_class_of_input_tags' ), 12, 5 );
		add_filter( 'gform_submit_button', array( $this, 'submit_button' ), 10, 2 );
	}

	/**
	 * Iterate through Gravity Forms, and conditionally append one to the end of posts.
	 *
	 * @return void.
	 */
	public function search_for_form_to_display_at_end_of_post() {
		$forms = \RGFormsModel::get_forms( null, 'title' );
		$this->manage_form_options( $forms );
	}

	/**
	 * Manage options for the forms.
	 *
	 * @param array $forms Gravity Forms that the Model returned.
	 * @return void
	 */
	public function manage_form_options( $forms ) {
		foreach ( $forms as $form ) {
			$this->maybe_append_form_to_post( $form );
			$this->maybe_display_form_horizontally( $form );
		}
	}

	/**
	 * Conditionally append form to the end of a post.
	 *
	 * @param object $form Gravity form to possible append.
	 * @return void
	 */
	public function maybe_append_form_to_post( $form ) {
		if ( $this->do_append_form_to_post( $form->id ) ) {
			$this->append_form_to_single_post_page( $form->id );
		}
	}

	/**
	 * Find whether to append a form to the end of the post.
	 *
	 * @param int $form_id ID of the Gravity Form.
	 * @return boolean $do_append Whether to append the form
	 */
	public function do_append_form_to_post( $form_id ) {
		$form = \GFAPI::get_form( $form_id );
		return ( ( isset( $form['aga_bottom_of_post'] ) ) && ( '1' === $form['aga_bottom_of_post'] ) );
	}

	/**
	 * Add a form to the end of a single post page.
	 *
	 * @param int $form_id ID of the Gravity Form.
	 * @return void.
	 */
	public function append_form_to_single_post_page( $form_id ) {
		if ( is_single() && ( 'post' === get_post_type() ) ) {
			$form = new AGA_Form( $form_id );
			add_filter( 'the_content', array( $form, 'append_form_to_content' ), 100 );
		}
	}

	/**
	 * Conditionally display the form horizontally.
	 *
	 * @param object $form Gravity form.
	 * @return void.
	 */
	public function maybe_display_form_horizontally( $form ) {
		if ( $this->do_display_horizontally( $form->id ) ) {
			$this->display_form_horizontally( $form->id );
		}
	}

	/**
	 * Whether to display the form horizontally.
	 *
	 * @param int $form_id ID of the Gravity Form.
	 * @return boolean $do_display_horizontally Whether to show the form length-wise.
	 */
	public function do_display_horizontally( $form_id ) {
		$form = \GFAPI::get_form( $form_id );
		return ( ( isset( $form['aga_horizontal_display'] ) ) && ( '1' === $form['aga_horizontal_display'] ) );
	}

	/**
	 * Show the form horizontally.
	 *
	 * @param int $form_id ID of the Gravity Form.
	 * @return void.
	 */
	public function display_form_horizontally( $form_id ) {
		$form = \GFAPI::get_form( $form_id );
		\GFAPI::update_form( $this->add_horizontal_display( $form ), $form_id );
	}

	/**
	 * Add a class to display the form horizontally.
	 *
	 * @param object $form Gravity form.
	 * @return object $form With altered property.
	 */
	public function add_horizontal_display( $form ) {
		if ( $this->form_does_not_have_any_classes( $form ) ) {
			$form['cssClass'] = 'gform_inline';
		} elseif ( $this->form_has_classes_but_not_an_inline_class( $form ) ) {
			$form['cssClass'] = $form['cssClass'] . ' gform_inline';
		}
		return $form;
	}

	/**
	 * Find if the form has no classes.
	 *
	 * @param object $form Gravity form.
	 * @return boolean $does_not_have_classes If the form has no classes.
	 */
	public function form_does_not_have_any_classes( $form ) {
		return ( ( isset( $form['cssClass'] ) && ( '' === $form['cssClass'] ) ) );
	}

	/**
	 * Find if the form has classes, but no inline class.
	 *
	 * @param object $form Gravity form.
	 * @return boolean $has_classes_but_no_inline If the form has classes, but not 'gform_inline'.
	 */
	public function form_has_classes_but_not_an_inline_class( $form ) {
		return ( ( isset( $form['cssClass'] ) ) && ( false === strpos( $form['cssClass'] , 'gform_inline' ) ) );
	}

	/**
	 * Filter callback to add a class to the input element.
	 *
	 * @param string  $content Field content.
	 * @param object  $field For the input tag.
	 * @param string  $value Initial value of the field.
	 * @param integer $lead_id Set to $entry_id or 0.
	 * @param int     $form_id ID of the Gravity Form.
	 * @return string $content Filtered, and now includes a class in the <input> elements.
	 */
	public function set_class_of_input_tags( $content, $field, $value, $lead_id, $form_id ) {

		/**
		* New class(es) for Gravity Form inputs.
		*
		* Add class(es) to input elements of type "text" or "email".
		*
		* @param string $class New class(es) of the input, space-separated.
		* @param int $form_id The id of the Gravity Form.
		*/
		$new_class = apply_filters( 'gravity_form_input_class', $this->default_class_of_input, $form_id );

		return $this->add_class_to_input( $content, esc_attr( $new_class ) );
	}

	/**
	 * Add a class to the input element.
	 *
	 * @param string $content Field content.
	 * @param string $new_class To add to <input>.
	 * @return string $content Now includes the new class.
	 */
	public function add_class_to_input( $content, $new_class ) {
		return preg_replace( "/(<input[^>]*?type=\'(text|email)\'[^>]*?(class=\'))/", '$1' . esc_attr( $new_class ) . '\s', $content );
	}

	/**
	 * Add classes to the submit button.
	 *
	 * @param string $button_input Button to filter.
	 * @param object $form Current Gravity form.
	 * @return string $filtered_button Markup of button, with new class(es).
	 */
	public function submit_button( $button_input, $form ) {

		/**
		* New class(es) for Gravity Form submit buttons.
		*
		* @param string $class New class(es) of the input, space-separated.
		* @param object $form The current form.
		*/
		$new_classes = apply_filters( 'submit_button_classes' , 'btn btn-primary btn-med' , $form );

		$class_attribute = "class='";
		if ( false !== strpos( $button_input, $class_attribute ) ) {
			$class_attribute_with_new_classes = $class_attribute . esc_attr( $new_classes ) . '\s';
			return str_replace( $class_attribute, $class_attribute_with_new_classes, $button_input );
		} else {
			$opening_input = '<input';
			$input_with_new_classes = $opening_input . ' class="' . esc_attr( $new_classes ) . '"';
			return str_replace( $opening_input, $input_with_new_classes, $button_input );
		}
	}

}
