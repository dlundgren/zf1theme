<?php
/**
 * Zend Framework 1 Theme Extension
 *
 * @author  David Lundgren
 * @license MIT
 */
namespace ZfTheme\DefinitionProvider;

/**
 * PHP Array definition provider
 * 
 * Store theme information in an array. Mostly useful for testing.
 *
 * @package ZfTheme\DefinitionProvider\Filesystem
 */
class PhpArray
	implements DefinitionProvider
{
	/**
	 * @var array The array data
	 */
	private $data = array();

	public function __construct($ary)
	{
		$this->data = $ary;
	}

	/**
	 * @param $id
	 * @return array|null
	 */
	public function find($id)
	{
		if (isset($this->data[$id])) {
			return $this->data[$id];
		}

		return null;
	}
}