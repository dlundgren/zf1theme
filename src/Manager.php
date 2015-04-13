<?php
/**
 * Zend Framework 1 Theme Extension
 *
 * @author  David Lundgren
 * @license MIT
 */
namespace ZfTheme;

use ZfTheme\DefinitionProvider\DefinitionProvider;

/**
 * Theme Manager
 * 
 * Used to find themes
 *
 * @package ZfTheme
 */
class Manager
{
	/**
	 * @var array The theme definition providers
	 */
	private $providers = array();

	/**
	 * @var \Zend_View The view that is used when creating a new theme to assist it's registration
	 */
	private $view;

	/**
	 * @var array list of instantiated themes that are shared
	 */
	private $themes = array();

	/**
	 * CLass Constructor
	 *
	 * Registers the providers with the manager
	 *
	 * @param $providers
	 */
	public function __construct($providers)
	{
		$this->setProviders($providers);
	}

	/**
	 * Sets the providers
	 *
	 * @param $providers
	 * @throws \InvalidArgumentException
	 * @return $this
	 */
	public function setProviders($providers)
	{
		!is_array($providers) && $providers = array($providers);
		foreach ($providers as $provider) {
			if (!($provider instanceof DefinitionProvider)) {
				throw new \InvalidArgumentException(get_class($provider) . ' must implement ZfTheme\DefinitionProvider\DefinitionProvider interface');
			}
		}

		$this->providers = $providers;

		return $this;
	}

	/**
	 * Adds a provider to the list of providers
	 *
	 * @param DefinitionProvider $provider
	 * @return $this
	 */
	public function addProvider(DefinitionProvider $provider)
	{
		$this->providers[] = $provider;

		return $this;
	}

	/**
	 * Returns the list of providers
	 *
	 * @return array
	 */
	public function getProviders()
	{
		return $this->providers;
	}

	/**
	 * Sets the view to use for theme registration
	 *
	 * @param \Zend_View $view
	 * @return $this
	 */
	public function setView(\Zend_View $view)
	{
		$this->view = $view;

		return $this;
	}

	/**
	 * Returns the current view used
	 *
	 * @return \Zend_View
	 */
	public function getView()
	{
		return $this->view;
	}

	/**
	 * Returns a new theme
	 *
	 * @param $name
	 */
	public function newTheme($name)
	{
		foreach ($this->providers as $provider) {
			if ($options = $provider->find($name)) {
				break;
			}
		}

		if (empty($options)) {
			throw new \RuntimeException("Theme $name could not be found");
		}

		return $this->initTheme($options);
	}

	/**
	 * Initialize the theme
	 *
	 * @param $options
	 * @return mixed
	 */
	protected function initTheme($options)
	{
		// we need to load the theme
		$themeClass = 'ZfTheme\GenericTheme';
		if (isset($options['class'])) {
			if (!class_exists($options['class'])) {
				require_once "{$options['path']}/theme.php";
			}
			$themeClass = $options['class'];
		}
		$theme = new $themeClass($options);
		$view  = $this->getView();

		// load any parent themes
		if ($theme->hasParentThemes()) {
			foreach ($theme->getParentThemes() as $name) {
				if ($name instanceof Theme) {
					continue;
				}

				// @todo not sure if these should be shared or not but it seems more right to make them shared ^ dlundgren
				$parent = $this->sharedTheme($name);
				$view && $parent->registerWithView($view);
				$theme->setParentTheme($name, $parent);
			}
		}

		$view && $theme->registerWithView($view);

		return $theme;
	}

	/**
	 * Returns a shared instance of a theme
	 *
	 * @param $name
	 * @return mixed
	 */
	public function sharedTheme($name)
	{
		if (!isset($this->themes[$name])) {
			$this->themes[$name] = $this->newTheme($name);
		}

		return $this->themes[$name];
	}
}