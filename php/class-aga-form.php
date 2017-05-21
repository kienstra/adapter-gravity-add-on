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
	 * Whether to use ajax by default
	 *
	 * @var boolean
	 */
	private $do_use_ajax_by_default = true;

	/**
	 * Instantiate the class.
	 *
	 * @param int $form_id Gravity Form ID.
	 */
	public function __construct( $form_id ) {
		$this->form_id = $form_id;
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
	public function append_form_to_content( $content ) {

		/**
		* Whether to use ajax in the Gravity Form at the bottom of a single post.
		*
		* @param boolean $do_ajax Whether to use ajax.
		*/
		$do_ajax = apply_filters( 'aga_use_ajax_in_form_at_bottom_of_single_post', $this->do_use_ajax_by_default );

		return $content . gravity_form( $this->form_id, false, false, false, '', $do_ajax, 1, false );
	}

}
