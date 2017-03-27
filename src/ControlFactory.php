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

use BrightNucleus\AdminPage\Control\Control;
use BrightNucleus\AdminPage\Exception\FailedToCreateControl;
use BrightNucleus\Config\ConfigTrait;
use BrightNucleus\Config\ConfigInterface as Config;
use BrightNucleus\Config\Config as BaseConfig;
use BrightNucleus\Config\Exception\FailedToProcessConfigException;
use BrightNucleus\Invoker\InstantiatorTrait;
use BrightNucleus\OptionsStore\Option;

/**
 * Class ControlFactory.
 *
 * Create form controls that match options (or arbitrary classes).
 *
 * @since   0.1.2
 *
 * @package BrightNucleus\AdminPage
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class ControlFactory {

	use ConfigTrait;
	use InstantiatorTrait;

	/**
	 * Instantiate a ViewBuilder object.
	 *
	 * @since 0.1.0
	 *
	 * @param Config $config Optional. Configuration settings.
	 *
	 * @throws FailedToProcessConfigException If the config could not be
	 *                                        processed.
	 */
	public function __construct( Config $config = null ) {
		$this->processConfig( $this->getConfig( $config ) );

	}

	/**
	 * Create a new control instance for a given option.
	 *
	 * @since 0.1.2
	 *
	 * @param Option $option Option for which to instantiate a new control.
	 * @param array  $args   Optional. Array of arguments to pass to the
	 *                       control's constructor.
	 *
	 * @return Control
	 * @throws FailedToCreateControl If no control mapping was found for the
	 *                               option.
	 */
	public function createFromOption( Option $option, array $args = [] ) {
		$class          = get_class( $option );
		$args['option'] = $option;

		$control_class = false;

		if ( $this->hasConfigKey( $class ) ) {
			$control_class = $this->getConfigKey( $class );
		}

		if ( array_key_exists( Control::TYPE, $args ) ) {
			$control_class = $args[ Control::TYPE ];
		}

		if ( ! is_subclass_of( $control_class, Control::class ) ) {
			throw FailedToCreateControl::forClass( $class );
		}

		return $this->instantiateClass( $control_class, $args );
	}

	/**
	 * Add additional Option => Control mappings to the factory config.
	 *
	 * @since 0.2.0
	 *
	 * @param array $mappings Array of Option => Control mappings.
	 */
	public function addMappings( array $mappings ) {
		$this->config = new BaseConfig(
			array_merge_recursive(
				$this->config->getArrayCopy(),
				$mappings
			)
		);
	}

	/**
	 * Get the configuration to use in the ViewBuilder.
	 *
	 * @since 0.2.0
	 *
	 * @return Config Configuration passed in through the constructor.
	 */
	protected function getConfig( $config = null ) {
		$defaults = new BaseConfig(
			__DIR__ . '/../config/defaults.php',
			null,
			null,
			'.'
		);

		$config = $config
			? new BaseConfig(
				array_merge_recursive(
					$defaults->getArrayCopy(),
					$config->getArrayCopy()
				),
				null,
				null,
				'.'
			)
			: $defaults;

		return $config->getSubConfig( 'BrightNucleus.AdminPage.ControlFactory' );
	}
}
