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
 * Class AdminSubPage
 *
 * A single WordPress backend page attached as a sub-page to another AdminPage.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\AdminPage
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class AdminSubPage extends AdminPage {

	const PARENT_SLUG = 'parent_slug';

	/**
	 * Get the WordPress fuction that is responsible for registering the page.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	protected function get_registration_function(): string {
		return 'add_submenu_page';
	}

	/**
	 * Get the list of required keys that the Config array needs to contain.
	 *
	 * @since 0.1.0
	 *
	 * @return string[] Array of config keys.
	 */
	protected function get_required_keys(): array {
		return [
			self::MENU_SLUG,
			self::MENU_TITLE,
			self::PARENT_SLUG,
			self::VIEW,
		];
	}
}
