<?php
/**
 * Zend Framework 1 Theme Extension
 *
 * @author  David Lundgren
 * @license MIT
 */
namespace ZfTheme;

/**
 * Generic Theme implementation
 *
 * @package ZfTheme
 */
class GenericTheme
	implements Theme
{
	/**
	 * @var string Id for this Theme
	 */
	protected $id;

	/**
	 * @var string Path to this theme on the file system
	 */
	protected $path;

	/**
	 * @var array List of themes that this theme extends
	 */
	protected $parentThemes = array();

	/**
	 * Constructor
	 *
	 * @param array $options
	 */
	public function __construct(array $options)
	{
		$this->options = $options;

		if (array_key_exists('uses', $options)) {
			foreach ($this->options['uses'] as $name) {
				$this->parentThemes[$name] = $name;
			}
		}
	}

	/**
	 * Returns the theme id.
	 *
	 * This is going to most often be the directory name in the theme storage directory
	 *
	 * @return string
	 */
	public function getId()
	{
		return $this->options['id'];
	}

	/**
	 * Returns the path to the theme
	 *
	 * @return string
	 */
	public function getPath()
	{
		return $this->options['path'];
	}

	/**
	 * Returns whether or not there are parent themes
	 *
	 * @return bool|mixed
	 */
	public function hasParentThemes()
	{
		return !empty($this->parentThemes);
	}

	/**
	 * Returns the parent themes
	 *
	 * @return array
	 */
	public function getParentThemes()
	{
		return empty($this->parentThemes) ? array() : $this->parentThemes;
	}

	/**
	 * Sets the parent theme
	 *
	 * NOTE: this is typically used by the Manager to change the parent from a name to an object
	 *
	 * @param string $name
	 * @param Theme $theme
	 * @return \ZfTheme\Theme
	 */
	public function setParentTheme($name, Theme $theme)
	{
		$this->parentThemes[$name] = $theme;

		return $this;
	}

	/**
	 * Registers with the view
	 *
	 * This allows to register headLink, headScript, inlineScript, or other variables with the view.
	 *
	 * @param \Zend_View $view
	 * @return \ZfTheme\Theme
	 */
	public function registerWithView(\Zend_View $view)
	{
		return $this;
	}
}