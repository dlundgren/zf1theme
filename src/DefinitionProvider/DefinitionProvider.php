<?php
/**
 * Zend Framework 1 Theme Extension
 *
 * @author  David Lundgren
 * @license MIT
 */
namespace ZfTheme\DefinitionProvider;

/**
 * Interface DefinitionProvider
 *
 * The contract for how to obtain Theme definitions
 *
 * @package ZfTheme\DefinitionProvider
 */
interface DefinitionProvider
{
	/**
	 * Returns the theme definition
	 *
	 * @param $id
	 * @return array|null
	 */
	public function find($id);
}