<?php
/**
 * Zend Framework 1 Theme Extension
 *
 * @author  David Lundgren
 * @license MIT
 */
namespace ZfTheme\Controller\Action\Helper;

/**
 * Zend Framework 1 Controller helper for dealing with the theme
 *
 * @package ZfTheme\Controller\Action\Helper
 */
class Theme
	extends \Zend_Controller_Action_Helper_Abstract
{
	/**
	 * @var \ZfTheme\Theme
	 */
	private $theme;

	/**
	 * Sets the theme on the view
	 *
	 * @param \ZfTheme\Theme $theme
	 */
	public function setTheme(\ZfTheme\Theme $theme)
	{
		$this->theme = $theme;
		$view        = $this->getActionController()->view;
		$view->theme($theme);

		// @todo should we registerWithView at this point or in the Manager::initTheme ^ dlundgren
	}

	/**
	 * Return the current theme
	 *
	 * @return \ZfTheme\Theme
	 */
	public function getTheme()
	{
		return $this->theme;
	}
}