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

use BrightNucleus\AdminPage\Control\Control;
use BrightNucleus\AdminPage\ControlFactory;
use BrightNucleus\AdminPage\Form;
use BrightNucleus\AdminPage\SubmitButton;
use BrightNucleus\Config\ConfigInterface as Config;
use BrightNucleus\Config\ConfigTrait;
use BrightNucleus\OptionsStore\Option;
use BrightNucleus\OptionsStore\OptionsStore;
use BrightNucleus\OptionsStore\ValidationError;
use BrightNucleus\OptionsStore\ValidationErrorCollection;

/**
 * Class BaseForm.
 *
 * @since   0.1.2
 *
 * @package BrightNucleus\AdminPage\Form
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class BaseForm implements Form {

	use ConfigTrait;

	/**
	 * Options store to use for the form.
	 *
	 * @since 0.1.2
	 *
	 * @var OptionsStore
	 */
	protected $options;

	/**
	 * Control factory instance to use.
	 *
	 * @since 0.1.2
	 *
	 * @var ControlFactory
	 */
	protected $control_factory;

	/**
	 * Controls to use for the form.
	 *
	 * @since 0.1.2
	 *
	 * @var Control[]
	 */
	protected $controls;

	/**
	 * Submit button to use for the form.
	 *
	 * @since 0.1.2
	 *
	 * @var SubmitButton
	 */
	protected $submit;

	/**
	 * Instantiate a Form object.
	 *
	 * @since 0.1.2
	 *
	 * @param Config         $config          Config instance to use.
	 * @param OptionsStore   $options         Options to use.
	 * @param ControlFactory $control_factory Optional. Control factory
	 *                                        instance to use.
	 */
	public function __construct(
		Config $config,
		OptionsStore $options,
		ControlFactory $control_factory = null
	) {
		$this->processConfig( $config );
		$this->options = $options;
		if ( $this->is_submission() ) {
			$this->process_submission();
		}
		$this->control_factory = $control_factory ?? new ControlFactory();
	}

	/**
	 * Get the identifier of the form.
	 *
	 * @since 0.1.2
	 *
	 * @return string Identifier of the form.
	 */
	public function get_id() {
		return $this->getConfigKey( Form::ID );
	}

	/**
	 * Get the action identifier of the button.
	 *
	 * @since 0.1.2
	 *
	 * @return string Action identifier of the button.
	 */
	public function get_action() {
		return $this->getConfigKey( Form::ACTION );
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
		return sprintf(
			'<form id="%1$s" action="%2$s" method="post">'
			. wp_nonce_field( $this->get_action() )
			. '<table class="form-table"><tbody>',
			$this->get_id(),
			get_home_url(
				get_current_blog_id(),
				wp_unslash( $_SERVER['REQUEST_URI'] ),
				$this->get_scheme()
			)
		);
	}

	/**
	 * Render the controls associated with the form.
	 *
	 * @since 0.1.2
	 *
	 * @return string HTML representation of the form controls.
	 */
	public function controls() {
		if ( ! isset( $this->controls ) ) {
			$this->initialize_controls();
		}

		$output = '';

		foreach ( $this->controls as $control ) {
			$output .= $control->render(
				'<tr><th scope="row">%1$s</th><td>%2$s<br />%3$s</td></tr>'
			);
		}

		return $output;
	}

	/**
	 * Initialize the controls for this form.
	 *
	 * @since 0.1.2
	 */
	protected function initialize_controls() {
		$this->controls = [];

		if ( ! $this->hasConfigKey( Form::CONTROLS ) ) {
			return;
		}

		foreach ( $this->getConfigKey( Form::CONTROLS ) as $key => $args ) {
			$this->controls[ $key ] = $this->control_factory->createFromOption(
				$this->options->has( $key )
					? $this->options->get( $key )
					: new Option\NullOption( $key ),
				$args
			);
		}
	}

	/**
	 * Render the submit button.
	 *
	 * @since 0.1.2
	 *
	 * @return string HTML rendering of the submit button.
	 */
	public function submit() {
		if ( ! isset( $this->submit ) ) {
			$this->initialize_submit();
		}

		return $this->submit->render(
			'<tr><td>%1$s%2$s</td></tr>'
		);
	}

	/**
	 * Initialize the submit button for this form.
	 *
	 * @since 0.1.2
	 */
	protected function initialize_submit() {

		$label = $this->hasConfigKey( Form::SUBMIT_BUTTON )
			? $this->getConfigKey( Form::SUBMIT_BUTTON )
			: null;

		$this->submit = new SubmitButton( $this->get_action(), $label );
	}

	/**
	 * Render the form closing tag(s).
	 *
	 * @since 0.1.2
	 *
	 * @return string HTML rendering of the button.
	 */
	public function end() {
		return '</tbody></table></form>';
	}

	/**
	 * Render the entire form in one go.
	 *
	 * @since 0.1.2
	 *
	 * @return string HTML rendering of the form.
	 */
	public function render() {
		return $this->start()
		       . $this->controls()
		       . $this->submit()
		       . $this->end();
	}

	/**
	 * Check whether the current request is a submission.
	 *
	 * @since 0.1.3
	 *
	 * @return bool Whether the current request is a submission.
	 */
	protected function is_submission(): bool {
		return 'POST' === $_SERVER['REQUEST_METHOD'];
	}

	/**
	 * Process a form submission.
	 *
	 * @since 0.1.2
	 */
	protected function process_submission() {
		$updated = false;

		$validation_errors = $this->get_validation_errors( (array) $_POST );
		if ( count( $validation_errors ) > 0 ) {
			$_POST["{$this->get_action()}_validation_errors"] = $validation_errors;

			return;
		}

		foreach ( $this->options->getAll() as $option ) {
			/** @var Option $option */
			$key = $option->getKey();

			if ( ! isset( $_POST[ $key ] ) ) {
				continue;
			}

			$option->setValue( $_POST[ $key ] );
			$updated = true;
		}

		$redirect_url = get_home_url(
			get_current_blog_id(),
			$_POST['_wp_http_referer'],
			$this->get_scheme()
		);

		if ( $updated ) {
			$redirect_url = add_query_arg(
				[ 'updated' => $updated ],
				$redirect_url
			);
		}

		wp_safe_redirect( $redirect_url, 200 );
		exit;
	}

	/**
	 * Check whether the submission array is valid and return all validation
	 * errors.
	 *
	 * @since 0.1.2
	 *
	 * @param array $submission Array of submission values.
	 *
	 * @return ValidationErrorCollection Collection of validation errors.
	 */
	protected function get_validation_errors( array $submission ) {
		$validation_errors = new ValidationErrorCollection();

		foreach ( $this->options->getAll() as $option ) {
			/** @var Option $option */
			$key = $option->getKey();

			if ( ! isset( $submission[ $key ] ) ) {
				continue;
			}

			$result = $option->validate( $submission[ $key ] );
			if ( $result instanceof ValidationError ) {
				$validation_errors->add( $result );
			}
		}

		return $validation_errors;
	}

	/**
	 * Check whether the current request is secure (using HTTPS).
	 *
	 * @since 0.1.2
	 *
	 * @return bool Whether the curent request is secure.
	 */
	protected function is_secure() {
		return isset( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] === 'on';
	}

	/**
	 * Get the current HTTP scheme.
	 *
	 * @since 0.1.2
	 *
	 * @return string Current HTTP scheme, either 'http' or 'https'.
	 */
	protected function get_scheme() {
		return $this->is_secure()
			? 'https'
			: 'http';
	}
}
