<?php
/**
 * Controller for Adapter Gravity Add On
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

/**
 * Class Controller
 */
class Controller {

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
		add_action( 'gform_pre_form_settings_save', array( $this, 'handle_horizontal_display' ) );
		add_filter( 'the_content', array( $this, 'conditionally_append_form' ), 100 );
		add_filter( 'gform_field_content', array( $this, 'set_class_of_input_tags' ), 12, 5 );
		add_filter( 'gform_submit_button', array( $this, 'submit_button' ), 10, 2 );
	}

	/**
	 * Iterate through Gravity Forms, and conditionally append one to the end of posts.
	 *
	 * @param array $form The form that is shown.
	 * @return array $form With additional settings.
	 */
	public function handle_horizontal_display( $form ) {
		$forms = \RGFormsModel::get_forms( null, 'title' );
		foreach ( $forms as $form ) {
			return $this->conditionally_display_form_horizontally( $form );
		}
	}

	/**
	 * Conditionally append a form to the post content.
	 *
	 * @param string $content Post content.
	 * @return string $content Post content, possibly filtered.
	 */
	public function conditionally_display_form_horizontally( $content ) {
		$forms = \RGFormsModel::get_forms( null, 'title' );
		foreach ( $forms as $form ) {
			$this->maybe_display_form_horizontally( $form );
		}
		return $content;
	}

	/**
	 * Conditionally append a form to the post content.
	 *
	 * @todo: clarify how the the $form is different from $form.
	 * @param string $content Post content.
	 * @return string $content Post content, possibly filtered.
	 */
	public function conditionally_append_form( $content ) {
		$forms = \RGFormsModel::get_forms( null, 'title' );
		foreach ( $forms as $form ) {
			if ( $this->do_append_form_to_post( \GFAPI::get_form( $form->id ) ) ) {
				return $this->append_form_to_content( $form->id, $content );
			}
		}
		return $content;
	}

	/**
	 * Whether to append a form to the end of the post.
	 *
	 * @param array $form Gravity Form.
	 * @return boolean $do_append Whether to append the form to the post content.
	 */
	public function do_append_form_to_post( $form ) {
		$do_append = (
			isset( $form['aga_bottom_of_post'] )
			&&
			( '1' === $form['aga_bottom_of_post'] )
			&&
			is_single()
			&&
			( 'post' === get_post_type() )
		);
		return $do_append;
	}

	/**
	 * Append Gravity Form to the end of the post content.
	 *
	 * Filter callback for 'the_content.'
	 * Use the form that this class processed.
	 *
	 * @param int    $form_id ID of the Gravity Form.
	 * @param string $content Post content to filter.
	 * @return string $content Filtered post content markup.
	 */
	public function append_form_to_content( $form_id, $content ) {

		/**
		* Whether to use ajax in the Gravity Form at the bottom of a single post.
		*
		* @param boolean $do_ajax Whether to use ajax.
		*/
		$do_ajax = apply_filters( 'aga_use_ajax_in_form_at_bottom_of_single_post', $this->do_use_ajax_by_default );

		return $content . gravity_form( $form_id, false, false, false, '', $do_ajax, 1, false );
	}

	/**
	 * Conditionally display the form horizontally.
	 *
	 * @param object $form Gravity form.
	 * @return array $form Gravity form.
	 */
	public function maybe_display_form_horizontally( $form ) {
		$full_form = \GFAPI::get_form( $form->id );
		if ( isset( $full_form['aga_horizontal_display'] ) && ( '1' === $full_form['aga_horizontal_display'] ) ) {
			// @todo: Fix error that begins here.
			return \GFAPI::update_form( $this->add_horizontal_display( $full_form ), $full_form->id );
		}
		return $full_form;
	}

	/**
	 * Add a class to display the form horizontally.
	 *
	 * @param array $form Gravity form.
	 * @return array $form With altered property.
	 */
	public function add_horizontal_display( $form ) {
		if ( ! isset( $form['cssClass'] ) ) {
			return $form;
		} elseif ( '' === $form['cssClass'] ) {
			$form['cssClass'] = 'gform_inline';
		} elseif ( false === strpos( $form['cssClass'], 'gform_inline' ) ) {
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
	public function form_does_not_have_any_class( $form ) {
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
