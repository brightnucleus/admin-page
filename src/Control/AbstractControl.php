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

use BrightNucleus\OptionsStore\Option;

/**
 * Abstract class AbstractControl.
 *
 * @since   0.1.2
 *
 * @package BrightNucleus\AdminPage\Control
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
abstract class AbstractControl implements Control {

	/**
	 * Option to attach to the control.
	 *
	 * @since 0.1.2
	 *
	 * @var Option
	 */
	protected $option;

	/**
	 * Label to use for the control.
	 *
	 * @since 0.1.2
	 *
	 * @var string
	 */
	protected $label;

	/**
	 * Description to add to the control.
	 *
	 * @since 0.1.2
	 *
	 * @var string
	 */
	protected $description;

	/**
	 * Placeholder text to use.
	 *
	 * @var string
	 *
	 * @since 0.1.2
	 */
	protected $placeholder;

	/**
	 * Instantiate an AbstractControl object.
	 *
	 * @since 0.1.2
	 *
	 * @param Option $option      Option that the control is attached to.
	 * @param string $label       Label to use for the control.
	 * @param string $description Description to add to the control.
	 * @param string $placeholder Placeholder text to use.
	 */
	public function __construct(
		Option $option,
		string $label = '',
		string $description = '',
		string $placeholder = ''
	) {
		$this->option      = $option;
		$this->label       = $label;
		$this->description = $description;
		$this->placeholder = $placeholder;
	}

	/**
	 * Get the option attached to the control.
	 *
	 * @since 0.1.2
	 *
	 * @return Option Option attached to the control.
	 */
	public function getOption() {
		return $this->option;
	}

	/**
	 * Get the label of the control.
	 *
	 * @since 0.1.2
	 *
	 * @return string Label of the control.
	 */
	public function getLabel() {
		return $this->label;
	}

	/**
	 * Get the description of the control.
	 *
	 * @since 0.1.2
	 *
	 * @return string Description of the control.
	 */
	public function getDescription() {
		return $this->description;
	}

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
	public function render( string $template = '%1$s%2$s%3$s' ) {
		return sprintf(
			$template,
			$this->renderLabel(),
			$this->renderInput(),
			$this->renderDescription()
		);
	}

	/**
	 * Render the label for the control.
	 *
	 * @since 0.1.2
	 *
	 * @return string HTML representation of the control label.
	 */
	public function renderLabel() {
		return sprintf(
			'<label for="%1$s">%2$s</label>',
			$this->option->getKey(),
			$this->getLabel()
		);
	}

	/**
	 * Render the description for the control.
	 *
	 * @since 0.1.2
	 *
	 * @return string HTML representation of the control description.
	 */
	public function renderDescription() {
		return sprintf(
			'<p>%1$s</p>',
			$this->getDescription()
		);
	}

	/**
	 * Render the input field for the control.
	 *
	 * @since 0.1.2
	 *
	 * @return string HTML representation of the control input.
	 */
	abstract public function renderInput();
}
