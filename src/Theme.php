<?php
/**
 * Zend Framework 1 Theme Extension
 *
 * @author  David Lundgren
 * @license MIT
 */
namespace ZfTheme;

/**
 * Theme interface
 *
 * The contract for what our theme's are expected to have
 *
 * @package ZfTheme
 */
interface Theme
{
	/**
	 * Returns the theme id.
	 *
	 * This is going to most often be the directory name in the theme storage directory
	 *
	 * @return string
	 */
	public function getId();

	/**
	 * Returns the path to the theme
	 *
	 * @return string
	 */
	public function getPath();

	/**
	 * Registers with the view
	 *
	 * This should register headLink, headScript, inlineScript, or other variables as desired
	 *
	 * @param \Zend_View $view
	 * @return \ZfTheme\Theme
	 */
	public function registerWithView(\Zend_View $view);

	/**
	 * Returns whether or not this theme uses other themes
	 *
	 * @return \ZfTheme\Theme
	 */
	public function hasParentThemes();

	/**
	 * Returns the themes that this
	 *
	 * @return \ZfTheme\Theme
	 */
	public function getParentThemes();

	/**
	 * Sets the parent theme
	 *
	 * Typically used by the manager to convert string parents into object parents
	 *
	 * @param string $name
	 * @param Theme  $theme
	 * @return \ZfTheme\Theme
	 */
	public function setParentTheme($name, Theme $theme);
}