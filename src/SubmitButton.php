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
 * Class SubmitButton.
 *
 * @since   0.1.2
 *
 * @package BrightNucleus\AdminPage
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class SubmitButton {

	/**
	 * Instantiate a SubmitButton object.
	 *
	 * @since 0.1.2
	 *
	 * @param string      $action Action identifier to pass to the submission
	 *                            handler.
	 * @param string|null $label  Optional. Label printed on the button.
	 */
	public function __construct( string $action, $label = 'Submit' ) {
		$this->action = $action;
		$this->label  = $label;
	}

	/**
	 * Get the action identifier of the button.
	 *
	 * @since 0.1.2
	 *
	 * @return string Action identifier of the button.
	 */
	public function getAction() {
		return $this->action;
	}

	/**
	 * Get the label of the button.
	 *
	 * @since 0.1.2
	 *
	 * @return string Label of the button.
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * Render the button as an HTML element.
	 *
	 * @since 0.1.2
	 *
	 * @param string $template Template to render the button into. This gets
	 *                         passed into `sprintf()` with the following
	 *                         placeholders:
	 *                         - %1$s : hidden 'action' field
	 *                         - %2$s : button input field
	 *
	 * @return string HTML rendering of the button.
	 */
	public function render( $template = '%1$s%2$s' ) {
		return sprintf(
			$template,
			$this->renderAction(),
			$this->renderInput()
		);
	}

	/**
	 * Render the button's input field as an HTML element.
	 *
	 * @since 0.1.2
	 *
	 * @return string HTML rendering of the button input.
	 */
	public function renderAction() {
		return sprintf(
			'<input type="hidden" name="action" value="%1$s">',
			$this->getAction()
		);
	}

	/**
	 * Render the button's input field as an HTML element.
	 *
	 * @since 0.1.2
	 *
	 * @return string HTML rendering of the button input.
	 */
	public function renderInput() {
		return sprintf(
			'<p class="submit"><input type="submit" value="%1$s" class="button button-primary button-large"></p>',
			$this->getLabel()
		);
	}

	/**
	 * Have the button be rendered to HTML when the object is invoked.
	 *
	 * @since 0.1.2
	 *
	 * @return string HTML rendering of the button.
	 */
	public function __invoke() {
		return $this->render();
	}
}
