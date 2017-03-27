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

$control_factory = [
	'BrightNucleus\OptionsStore\Option\EmailAddressOption' => Control\Text::class,
	'BrightNucleus\OptionsStore\Option\IntegerOption'      => Control\Text::class,
	'BrightNucleus\OptionsStore\Option\StringOption'       => Control\Text::class,
];

return [
	'BrightNucleus' => [
		'AdminPage' => [
			'ControlFactory' => $control_factory,
		],
	],
];
