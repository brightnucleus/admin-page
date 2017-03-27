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

namespace BrightNucleus\AdminPage\Control;

use BrightNucleus\Values\EscapeTarget;

/**
 * Class ReadOnlyText.
 *
 * @since   0.1.2
 *
 * @package BrightNucleus\AdminPage\Control
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class ReadOnlyText extends AbstractControl {

	/**
	 * Render the input field for the control.
	 *
	 * @since 0.1.2
	 *
	 * @return string HTML representation of the control input.
	 */
	public function renderInput() {
		return sprintf(
			'<input type="text" name="%1$s" id="%1$s" value="%2$s" placeholder="%3$s" readonly>',
			$this->option->getKey(),
			$this->option->escape( EscapeTarget::ATTRIBUTE ),
			$this->placeholder
		);
	}
}
