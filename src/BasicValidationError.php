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

namespace BrightNucleus\AdminPage;

/**
 * Class BasicValidationError.
 *
 * @since   0.1.2
 *
 * @package BrightNucleus\AdminPage
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
final class BasicValidationError {

	/**
	 * Message describing the requirement.
	 *
	 * @since 0.1.2
	 *
	 * @var string
	 */
	private $key;

	/**
	 * Message describing the requirement.
	 *
	 * @since 0.1.2
	 *
	 * @var string
	 */
	private $message;

	/**
	 * Instantiate a BasicValidationError object.
	 *
	 * @since 0.1.2
	 *
	 * @param string $key     Key of the option that was not valid.
	 * @param string $message Message describing the requirement.
	 */
	public function __construct( string $key, string $message ) {
		$this->key     = $key;
		$this->message = $message;
	}

	/**
	 * Get the key of the option that was not valid.
	 *
	 * @since 0.1.2
	 *
	 * @return string Message describing the requirement.
	 */
	public function getKey() {
		return $this->key;
	}

	/**
	 * Get the message describing the requirement.
	 *
	 * @since 0.1.2
	 *
	 * @return string Message describing the requirement.
	 */
	public function getMessage() {
		return $this->message;
	}
}
