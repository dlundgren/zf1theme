<?php
/**
 * Zend Framework 1 Theme Extension
 *
 * @author David Lundgren
 * @license MIT
 */
namespace ZfTheme\View\Helper;

/**
 * Zend Framework 1 View Helper to ease usage of the theme in views
 *
 * @package ZfTheme\View\Helper
 */
class Theme
	extends \Zend_View_Helper_Abstract
{
	/**
	 * @var \ZfTheme\Theme
	 */
	private $theme;

	/**
	 * Handle theme settings
	 *
	 * @param \ZfTheme\Theme $theme
	 * @return \ZfTheme\Theme
	 */
	public function theme(\ZfTheme\Theme $theme = null)
	{
		if ($theme) {
			$this->theme = $this->view->theme = $theme;
		}
		else {
			return $this->theme;
		}
	}
}