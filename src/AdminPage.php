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

use BrightNucleus\AdminPage\Exception\MissingConfigKey;
use BrightNucleus\AdminPage\Form\BaseForm;
use BrightNucleus\AdminPage\Form\NullForm;
use BrightNucleus\Config\ConfigInterface as Config;
use BrightNucleus\Config\ConfigTrait;
use BrightNucleus\Dependency\DependencyManager as Dependencies;
use BrightNucleus\Exception\InvalidArgumentException;
use BrightNucleus\Invoker\FunctionInvokerTrait;
use BrightNucleus\OptionsStore\OptionsStore;
use BrightNucleus\View\ViewBuilder;
use BrightNucleus\Views;
use Closure;

/**
 * Class AdminPage.
 *
 * A single WordPress backend page attached to the main admin navigation.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\AdminPage
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class AdminPage {

	use FunctionInvokerTrait;
	use ConfigTrait;

	const CAPABILITY   = 'capability';
	const DEPENDENCIES = 'dependencies';
	const MENU_SLUG    = 'menu_slug';
	const MENU_TITLE   = 'menu_title';
	const PAGE_TITLE   = 'page_title';
	const VIEW         = 'view';
	const ICON_URL     = 'icon_url';
	const POSITION     = 'position';
	const FORM         = 'form';

	/**
	 * Hooks to the settings pages that have been registered.
	 *
	 * @since 0.1.0
	 *
	 * @var array
	 */
	protected $page_hook;

	/**
	 * Options store that handles option persistence.
	 *
	 * @since 0.1.0
	 *
	 * @var OptionsStore
	 */
	protected $options;

	/**
	 * Form instance that is used to render a form.
	 *
	 * @since 0.1.2
	 *
	 * @var Form
	 */
	protected $form;

	/**
	 * Dependency Manager that manages enqueueing of dependencies.
	 *
	 * @since 0.1.0
	 *
	 * @var Dependencies
	 */
	protected $dependencies;

	/**
	 * View builder that is used to build the views that need to be rendered.
	 *
	 * @since 0.1.0
	 *
	 * @var ViewBuilder
	 */
	protected $view_builder;

	/**
	 * Instantiate an AdminPage object.
	 *
	 * @since 0.1.0
	 *
	 * @param Config       $config       Config object that contains admin page
	 *                                   configuration.
	 * @param OptionsStore $options      Optional. Options store that manages
	 *                                   option persistence.
	 * @param Dependencies $dependencies Optional. Dependency manager that
	 *                                   handles enqueueing.
	 * @param ViewBuilder  $view_builder Optional. Custom view builder to use.
	 * @param Form         $form         Optional. Form instance to use.
	 */
	public function __construct(
		Config $config,
		OptionsStore $options = null,
		Dependencies $dependencies = null,
		ViewBuilder $view_builder = null,
		Form $form = null
	) {
		$this->processConfig( $config );
		$this->options      = $options;
		$this->dependencies = $dependencies;
		$this->view_builder = $view_builder ?? Views::getViewBuilder();
		if ( null === $form && $this->hasConfigKey( AdminPage::FORM ) ) {
			$form = new BaseForm(
				$this->config->getSubConfig( AdminPage::FORM ),
				$this->options
			);
		}
		$this->form = $form ?? new NullForm();
	}

	/**
	 * Register necessary hooks.
	 *
	 * @since 0.1.0
	 */
	public function register() {
		add_action( 'admin_menu', [ $this, 'register_page' ], 25 );
	}

	/**
	 * Add a single page to the WordPress admin backend.
	 *
	 * @since 0.1.0
	 *
	 * @throws InvalidArgumentException If the page addition function could not
	 *                                  be invoked.
	 * @throws MissingConfigKey         If a required config key is missing.
	 */
	public function register_page() {
		$config = $this->validate_config( $this->getConfigArray() );

		// Skip page creation if it already exists. This allows reuse of 1 page
		// for several plugins.
		if ( ! empty( $GLOBALS['admin_page_hooks'][ $config[ self::MENU_SLUG ] ] ) ) {
			return;
		}

		$function           = $this->get_registration_function();
		$config['function'] = $this->get_callback( $config );

		$this->page_hook = $this->invokeFunction( $function, $config );
	}

	/**
	 * Build the callback that will be used as the rendering function for the
	 * page.
	 *
	 * @since 0.1.0
	 *
	 * @param array $config Array of config entries that define the page.
	 *
	 * @return Closure
	 */
	protected function get_callback( array $config ) {
		return function () use ( $config ) {
			if ( array_key_exists( self::VIEW, $config ) ) {
				//$this->process_options_submission( $this->options, $_POST );

				$view = $this->view_builder->create( $config[ self::VIEW ] );
				echo $view->render( [
					'options' => $this->options,
					'form'    => $this->form,
				] );
			}

			if ( ! array_key_exists( self::DEPENDENCIES, $config ) ) {
				return;
			}
			array_walk(
				$config[ self::DEPENDENCIES ],
				[ $this, 'enqueue_dependency' ]
			);
		};
	}

	/**
	 * Validate the array of config values, to make sure they fit the WordPress
	 * function that needs to be called.
	 *
	 * @since 0.1.0
	 *
	 * @param array $entries Raw config entries that need to be validated.
	 *
	 * @return array Validated set of config entries.
	 * @throws MissingConfigKey If one of the required config keys was not
	 *                          provided.
	 */
	protected function validate_config( array $entries ): array {
		foreach ( $this->get_required_keys() as $key ) {
			if ( ! array_key_exists( $key, $entries ) ) {
				throw Exception\MissingConfigKey::forKey( $key );
			}
		}

		$defaults = [
			self::PAGE_TITLE => $entries[ self::MENU_TITLE ],
			self::CAPABILITY => 'manage_options',
		];

		return array_merge( $defaults, $entries );
	}

	/**
	 * Get the WordPress function that is responsible for registering the page.
	 *
	 * @since 0.1.0
	 *
	 * @return string
	 */
	protected function get_registration_function(): string {
		return 'add_menu_page';
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
			self::VIEW,
		];
	}

	/**
	 * Enqueue dependencies of a page.
	 *
	 * @since 0.1.0
	 *
	 * @param string $handle Handle of a dependency to enqueue.
	 */
	protected function enqueue_dependency( $handle ) {
		if ( null === $this->dependencies ) {
			return;
		}
		$this->dependencies->enqueue_handle( $handle );
	}
}
