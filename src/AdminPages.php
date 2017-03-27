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

use BrightNucleus\Config\ConfigFactory;
use BrightNucleus\Config\ConfigInterface as Config;
use BrightNucleus\Config\ConfigTrait;
use BrightNucleus\Dependency\DependencyManager as Dependencies;
use BrightNucleus\Invoker\FunctionInvokerTrait;
use BrightNucleus\OptionsStore\OptionsStore;
use BrightNucleus\View\ViewBuilder;
use BrightNucleus\Views;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class AdminPageCollection.
 *
 * @since   0.1.0
 *
 * @package BrightNucleus\AdminPage
 * @author  Alain Schlesser <alain.schlesser@gmail.com>
 */
class AdminPages extends ArrayCollection {

	use FunctionInvokerTrait;
	use ConfigTrait;

	/**
	 * Options store that handles option persistence.
	 *
	 * @since 0.1.0
	 *
	 * @var OptionsStore
	 */
	protected $options;

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
	 * Instantiate an AdminPages object.
	 *
	 * @since 0.1.0
	 *
	 * @param Config       $config       Config object that contains admin pages
	 *                                   configuration.
	 * @param OptionsStore $options      Options store that manages option
	 *                                   persistence.
	 * @param Dependencies $dependencies Dependency manager that handles
	 *                                   enqueueing.
	 * @param ViewBuilder  $view_builder Optional. Custom view builder to use.
	 */
	public function __construct(
		Config $config,
		OptionsStore $options = null,
		Dependencies $dependencies = null,
		ViewBuilder $view_builder = null
	) {
		$this->processConfig( $config );
		$this->options      = $options;
		$this->dependencies = $dependencies;
		$this->view_builder = $view_builder ?? Views::getViewBuilder();
		parent::__construct();
	}

	/**
	 * Register necessary hooks.
	 *
	 * @since 0.1.0
	 */
	public function register() {
		if ( ! is_admin() ) {
			return;
		}

		add_action( 'init', [ $this, 'register_pages' ], 20 );
	}

	/**
	 * Add the pages from the configuration settings to the collection and
	 * register them with the WordPress admin backend.
	 *
	 * @since 0.1.0
	 */
	public function register_pages() {
		$pages = $this->getConfigArray();
		array_walk( $pages, [ $this, 'add_page' ] );
		foreach ( $this as $page ) {
			/** @var AdminPage $page */
			$page->register();
		}
	}

	/**
	 * Add a single page to the collection of pages.
	 *
	 * This can deal with the following input types:
	 *  - instance of AdminPage
	 *  - closure returning an instance of AdminPage
	 *  - FQCN returning an instance of AdminPage
	 *
	 * @since 0.1.0
	 *
	 * @param mixed                     $config Config value(s) that need to be
	 *                                          passed to the page.
	 * @param AdminPage|callable|string $page   Instance of AdminPage or
	 *                                          string/closure that returns
	 *                                          one.
	 *
	 * @return bool
	 */
	private function add_page( $config, $page ): bool {
		if ( $page instanceof AdminPage ) {
			return $this->add( $page );
		}

		if ( is_callable( $page ) ) {
			$page = $page(
				$config_object ?? $config_object = ConfigFactory::create( $config ),
				$this->options,
				$this->dependencies,
				$this->view_builder
			);
		}

		if ( is_string( $page ) ) {
			$page = new $page(
				$config_object ?? $config_object = ConfigFactory::create( $config ),
				$this->options,
				$this->dependencies,
				$this->view_builder
			);
		}

		if ( $page instanceof AdminPage ) {
			return $this->add( $page );
		}

		return false;
	}
}
