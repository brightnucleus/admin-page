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
 * Class MissingConfigKey.
 *
 * This exception is thrown when the Config values passed to a method were
 * missing a required key.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\AdminPage\Exception
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class MissingConfigKey extends UnexpectedValueException implements AdminPageException {

	/**
	 * Get an instance of a MissingConfigKey exception for a missing key.
	 *
	 * @since 0.1.0
	 *
	 * @param string $key Key that was missing.
	 *
	 * @return MissingConfigKey
	 */
	public static function forKey( $key ) {
		$message = sprintf(
			'The Config values were missing the required key "%1$s".',
			$key
		);

		return new self( $message );
	}
}
