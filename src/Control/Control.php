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

/**
 * Interface Control.
 *
 * @since   0.1.2
 *
 * @package BrightNucleus\AdminPage\Control
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
interface Control {

	const LABEL       = 'label';
	const DESCRIPTION = 'description';
	const PLACEHOLDER = 'placeholder';
	const TYPE        = 'type';

	/**
	 * Render the HTML representation of the control.
	 *
	 * @since 0.1.2
	 *
	 * @param string $template Template to render the control into. This gets
	 *                         passed into `sprintf()` with the following
	 *                         placeholders:
	 *                         - %1$s : label
	 *                         - %2$s : input field
	 *                         - %3$s : description
	 *
	 * @return string HTML representation of the control.
	 */
	public function render( string $template = '%1$s%2$s%3$s' );
}
