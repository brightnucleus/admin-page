<?php
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

namespace BrightNucleus\AdminPage\Exception;

use BrightNucleus\Exception\UnexpectedValueException;

/**
 * Class FailedToCreateControl.
 *
 * This exception is thrown when the Config values passed to a method were
 * missing a required key.
 *
 * @since   0.1.2
 *
 * @package BrightNucleus\AdminPage\Exception
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class FailedToCreateControl extends UnexpectedValueException implements AdminPageException {

	/**
	 * Get an instance of a FailedToCreateControl exception for an unknown
	 * class.
	 *
	 * @since 0.1.2
	 *
	 * @param string $class Class for which the control could not be created.
	 *
	 * @return FailedToCreateControl
	 */
	public static function forClass( $class ) {
		$message = sprintf(
			'Could not create a form control for the class "%1$s".',
			$class
		);

		return new self( $message );
	}
}
