<?php declare( strict_types = 1 );
/**
 * Bright Nucleus Admin Page.
 *
 * Config-based WordPress admin pages using the Settings API.
 *
 * @package   BrightNucleus\AdminPage
 * @author    Alain Schlesser <alain.schlesser@gmail.com>
 * @license   MIT
 * @link      https://www.brightnucleus.com/
 * @copyright 2017 Alain Schlesser, Bright Nucleus
 */

namespace BrightNucleus\AdminPage\Form;

use BrightNucleus\AdminPage\Form;

/**
 * Interface Form.
 *
 * @since   0.1.2
 *
 * @package BrightNucleus\AdminPage
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class NullForm implements Form {

	/**
	 * Get the action identifier of the button.
	 *
	 * @since 0.1.2
	 *
	 * @return string Action identifier of the button.
	 */
	public function get_action() {
		return '';
	}

	/**
	 * Render the form starting tag(s).
	 *
	 * This includes the hidden input fields with the nonce and the referrer.
	 *
	 * @since 0.1.2
	 *
	 * @return string HTML rendering of the button.
	 */
	public function start() {
		return '';
	}

	/**
	 * Render the controls associated with the form.
	 *
	 * @since 0.1.2
	 *
	 * @return string HTML representation of the form controls.
	 */
	public function controls() {
		return '';
	}

	/**
	 * Render the submit button.
	 *
	 * @since 0.1.2
	 *
	 * @return string HTML rendering of the submit button.
	 */
	public function submit() {
		return '';
	}

	/**
	 * Render the form closing tag(s).
	 *
	 * @since 0.1.2
	 *
	 * @return string HTML rendering of the button.
	 */
	public function end() {
		return '';
	}

	/**
	 * Render the entire form in one go.
	 *
	 * @since 0.1.2
	 *
	 * @return string HTML rendering of the form.
	 */
	public function render() {
		return '';
	}
}
