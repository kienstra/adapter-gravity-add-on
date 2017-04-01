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
	private static $default_class_of_input = 'form-control';

	/**
	 * Construct the class.
	 */
	public function __construct() {
		add_action( 'template_redirect', array( $this, 'aga_search_for_form_to_display_at_end_of_post' ) );
		add_filter( 'gform_field_content', array( $this, 'aga_set_class_of_input_tags' ), 12, 5 );
		add_filter( 'gform_field_content', array( $this, 'aga_maybe_insert_placeholders_and_remove_labels' ), 11, 5 );
		add_filter( 'gform_submit_button', array( $this, 'aga_submit_button' ), 10, 2 );
	}

	/**
	 * Iterate through Gravity Forms, and conditionally append one to the end of posts.
	 *
	 * @return void.
	 */
	public function aga_search_for_form_to_display_at_end_of_post() {
		$forms = \RGFormsModel::get_forms( null, 'title' );
		$this->aga_manage_form_options( $forms );
	}

	/**
	 * Manage options for the forms.
	 *
	 * @param array $forms Gravity Forms that the Model returned.
	 * @return void
	 */
	public function aga_manage_form_options( $forms ) {
		foreach ( $forms as $form ) {
			$this->aga_maybe_append_form_to_end_of_post( $form );
			$this->aga_maybe_display_form_horizontally( $form );
		}
	}

	/**
	 * Conditionally append form to the end of a post.
	 *
	 * @param object $form Gravity form to possible append.
	 * @return void
	 */
	public function aga_maybe_append_form_to_end_of_post( $form ) {
		if ( $this->aga_do_append_form_to_end_of_post( $form->id ) ) {
			$this->aga_append_form_to_end_of_single_post_page( $form->id );
		}
	}

	/**
	 * Find whether to append a form to the end of the post.
	 *
	 * @param int $form_id ID of the Gravity Form.
	 * @return boolean $do_append Whether to append the form
	 */
	public function aga_do_append_form_to_end_of_post( $form_id ) {
		$form = \GFAPI::get_form( $form_id );
		return ( ( isset( $form['aga_bottom_of_post'] ) ) && ( '1' === $form['aga_bottom_of_post'] ) );
	}

	/**
	 * Add a form to the end of a single post page.
	 *
	 * @param int $form_id ID of the Gravity Form.
	 * @return void.
	 */
	public function aga_append_form_to_end_of_single_post_page( $form_id ) {
		if ( $this->aga_is_page_a_single_post() ) {
			AGA_Form::add_form( $form_id );
			add_filter( 'the_content' , array( 'AdapterGravityAddOn\AGA_Form', 'append_form_to_content' ), '100' );
		}
	}

	/**
	 * Conditionally display the form horizontally.
	 *
	 * @param object $form Gravity form.
	 * @return void.
	 */
	public function aga_maybe_display_form_horizontally( $form ) {
		if ( $this->aga_do_display_horizontally( $form->id ) ) {
			$this->aga_display_form_horizontally( $form->id );
		}
	}

	/**
	 * Whether to display the form horizontally.
	 *
	 * @param int $form_id ID of the Gravity Form.
	 * @return boolean $do_display_horizontally Whether to show the form length-wise.
	 */
	public function aga_do_display_horizontally( $form_id ) {
		$form = \GFAPI::get_form( $form_id );
		return ( ( isset( $form['aga_horizontal_display'] ) ) && ( '1' === $form['aga_horizontal_display'] ) );
	}

	/**
	 * Show the form horizontally.
	 *
	 * @param int $form_id ID of the Gravity Form.
	 * @return void.
	 */
	public function aga_display_form_horizontally( $form_id ) {
		$form = \GFAPI::get_form( $form_id );
		$form_with_horizontal_display = $this->aga_add_horizontal_display( $form );
		\GFAPI::update_form( $form_with_horizontal_display , $form_id );
	}

	/**
	 * Add a class to display the form horizontally.
	 *
	 * @param object $form Gravity form.
	 * @return object $form With altered property.
	 */
	public function aga_add_horizontal_display( $form ) {
		if ( $this->aga_form_does_not_have_any_classes( $form ) ) {
			$form['cssClass'] = 'gform_inline';
		} elseif ( $this->aga_form_has_classes_but_not_an_inline_class( $form ) ) {
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
	public function aga_form_does_not_have_any_classes( $form ) {
		return ( ( isset( $form['cssClass'] ) && ( '' === $form['cssClass'] ) ) );
	}

	/**
	 * Find if the form has classes, but no inline class.
	 *
	 * @param object $form Gravity form.
	 * @return boolean $has_classes_but_no_inline If the form has classes, but not 'gform_inline'.
	 */
	public function aga_form_has_classes_but_not_an_inline_class( $form ) {
		return ( ( isset( $form['cssClass'] ) ) && ( false === strpos( $form['cssClass'] , 'gform_inline' ) ) );
	}

	/**
	 * Find if the page is a single post.
	 *
	 * @return boolean $is_single_post If the page is single and of type 'post'.
	 */
	public function aga_is_page_a_single_post() {
		return ( is_single() && ( 'post' === get_post_type() ) );
	}

	/**
	 * Filter callback to conditionally add placeholders and remove labels.
	 *
	 * @param string  $content Field content.
	 * @param object  $field For the input tag.
	 * @param string  $value Initial value of the field.
	 * @param integer $lead_id Set to $entry_id or 0.
	 * @param int     $form_id ID of the Gravity Form.
	 * @return string $content Conditionally filtered field content.
	 */
	public function aga_maybe_insert_placeholders_and_remove_labels( $content, $field, $value, $lead_id, $form_id ) {
		if ( $this->is_form_set_to_show_aga_placeholder( $form_id ) ) {
			$placeholder = $field['label'];
			$content = $this->aga_get_content_with_placeholder_and_without_label( $content , $placeholder );
		}
		return $content;
	}

	/**
	 * Find whether the form's setting indicate that it shows a placeholder.
	 *
	 * @param int $form_id ID of the Gravity Form.
	 * @return boolean $is_set Whether the form should show a placeholder.
	 */
	public function is_form_set_to_show_aga_placeholder( $form_id ) {
		$form = \GFAPI::get_form( $form_id );
		return ( ( isset( $form['labelPlacement'] ) ) && ( 'in_placeholder' === $form['labelPlacement'] ) );
	}

	/**
	 * Get the content, having removed the label and inserted a placeholder.
	 *
	 * @param string $content Initial markup.
	 * @param string $placeholder Value of placeholder attribute.
	 * @return string $content Altered to include a placeholder, and without a label.
	 */
	public function aga_get_content_with_placeholder_and_without_label( $content, $placeholder ) {
		$content_with_placeholder = preg_replace( "/(<input[^>]*?type=\'(text|email)\')/" , "$1 placeholder='$placeholder'" , $content );
		$content_with_placeholder_and_without_label = preg_replace( '/<label.*?<\/label>/' , '' , $content_with_placeholder );
		return $content_with_placeholder_and_without_label;
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
	public function aga_set_class_of_input_tags( $content, $field, $value, $lead_id, $form_id ) {

		/**
		* New class(es) for Gravity Form inputs.
		*
		* Add class(es) to input elements of type "text" or "email".
		*
		* @param string $class New class(es) of the input, space-separated.
		* @param int $form_id The id of the Gravity Form.
		*/
		$new_class = apply_filters( 'aga_gravity_form_input_class', $this->$default_class_of_input, $form_id );

		$new_content = $this->aga_add_class_to_input( $content, esc_attr( $new_class ) );
		return $new_content;
	}

	/**
	 * Add a class to the input element.
	 *
	 * @param string $content Field content.
	 * @param string $new_class To add to <input>.
	 * @return string $content Now includes the new class.
	 */
	public function aga_add_class_to_input( $content, $new_class ) {
		$content_with_new_class = preg_replace( "/(<input[^>]*?type=\'(text|email)\'[^>]*?(class=\'))/", '$1' . esc_attr( $new_class ) . '\s', $content );
		return $content_with_new_class;
	}

	/**
	 * Add classes to the submit button.
	 *
	 * @param string $button_input Button to filter.
	 * @param object $form Current Gravity form.
	 * @return string $filtered_button Markup of button, with new class(es).
	 */
	public function aga_submit_button( $button_input, $form ) {

		/**
		* New class(es) for Gravity Form submit buttons.
		*
		* @param string $class New class(es) of the input, space-separated.
		* @param object $form The current form.
		*/
		$new_classes = apply_filters( 'aga_submit_button_classes' , 'btn btn-primary btn-med' , $form );

		$class_attribute = "class='";
		if ( false !== strpos( $button_input, $class_attribute ) ) {
			$class_attribute_with_new_classes = $class_attribute . esc_attr( $new_classes ) . '\s';
			$filtered_button = str_replace( $class_attribute, $class_attribute_with_new_classes, $button_input );
			return $filtered_button;
		} else {
			$opening_input = '<input';
			$input_with_new_classes = $opening_input . ' class="' . esc_attr( $new_classes ) . '"';
			$filtered_button = str_replace( $opening_input, $input_with_new_classes, $button_input );
			return $filtered_button;
		}
	}

}
