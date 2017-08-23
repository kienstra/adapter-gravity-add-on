<?php
/**
 * Email form, to optionally place at the end of a post.
 *
 * @package AdapterGravityAddOn
 */

namespace AdapterGravityAddOn;

/**
 * Class Email_Form
 */
class Email_Form {

	/**
	 * Instance of this plugin.
	 *
	 * @var object
	 */
	public $add_on;

	/**
	 * Class that causes a horizontal display.
	 *
	 * @var string
	 */
	public $horizontal_class = 'gform-inline';

	/**
	 * Key for the form's CSS class setting.
	 *
	 * @var string
	 */
	public $css_class_setting = 'cssClass';

	/**
	 * Default class of the input tags.
	 *
	 * @var string
	 */
	public $default_class_of_input = 'form-control';

	/**
	 * Default classes of the submit button.
	 *
	 * @var string
	 */
	public $default_submit_button_classes = 'btn btn-primary btn-med';

	/**
	 * Whether to use AJAX by default for the Gravity form.
	 *
	 * @var string
	 */
	public $use_ajax_by_default = true;

	/**
	 * Construct the class.
	 *
	 * @param object $add_on Instance of Plugin.
	 */
	public function __construct( $add_on ) {
		$this->add_on = $add_on;
	}

	/**
	 * Add the filters for the class.
	 *
	 * @return void
	 */
	public function init() {
		add_filter( 'gform_pre_render', array( $this, 'conditionally_display_form_horizontally' ) );
		add_filter( 'the_content', array( $this, 'conditionally_append_form' ), 100 );
		add_filter( 'gform_field_content', array( $this, 'set_class_of_input_tags' ), 12, 5 );
		add_filter( 'gform_submit_button', array( $this, 'submit_button' ), 10, 2 );
	}

	/**
	 * Conditionally append a form to the post content.
	 *
	 * @param array $form Gravity form.
	 * @return array $form Gravity form, possibly with altered content.
	 */
	public function conditionally_display_form_horizontally( $form ) {
		if ( isset( $form['aga_horizontal_display'] ) && ( '1' === $form['aga_horizontal_display'] ) ) {
			return $this->add_horizontal_display( $form );
		}
		return $form;
	}

	/**
	 * Add a class to display the form horizontally.
	 *
	 * @param array $form Gravity form.
	 * @return array $form Possibly with altered properties.
	 */
	public function add_horizontal_display( $form ) {
		$setting = 'cssClass';
		if ( ! isset( $form[ $this->css_class_setting ] ) ) {
			return $form;
		} elseif ( '' === $form[ $this->css_class_setting ] ) {
			$form[ $this->css_class_setting ] = $this->horizontal_class;
		} elseif ( false === strpos( $form[ $setting ], $this->horizontal_class ) ) {
			$form[ $this->css_class_setting ] = $form[ $this->css_class_setting ] . ' ' . $this->horizontal_class;
		}
		return $form;
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
			if ( isset( $form->id ) && $this->do_append_form_to_content( \GFAPI::get_form( $form->id ) ) ) {
				return $this->append_form_to_content( $form->id, $content );
			}
		}
		return $content;
	}

	/**
	 * Whether to append a form to the content.
	 *
	 * @param array $form Gravity Form.
	 * @return boolean $do_append Whether to append the form to the post content.
	 */
	public function do_append_form_to_content( $form ) {
		return (
			isset( $form[ $this->add_on->components['email_setting']->bottom_of_post ] )
			&&
			( '1' === $form[ $this->add_on->components['email_setting']->bottom_of_post ] )
			&&
			is_single()
			&&
			( 'post' === get_post_type() )
		);
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
		$do_ajax = apply_filters( 'aga_use_ajax_in_form_at_bottom_of_single_post', $this->use_ajax_by_default );
		if ( ! is_bool( $do_ajax ) ) {
			$do_ajax = $this->use_ajax_by_default;
		}

		return $content . gravity_form( $form_id, false, false, false, '', $do_ajax, 1, false );
	}

	/**
	 * Filter callback to add a class to the input element.
	 *
	 * @action gform_field_content
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
	 * Get the submit button with added classes.
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
		$new_classes = apply_filters( 'aga_submit_button_classes', $this->default_submit_button_classes, $form );

		$class_attribute = 'class="';
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
