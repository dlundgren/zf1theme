<?php
namespace ZfTheme\Test;

use ZfTheme\DefinitionProvider\DefinitionProvider;

/**
 * Definition Provider used during tests
 *
 * @package ZfTheme\Test
 */
class EchoDefinitionProvider
	implements DefinitionProvider
{
	/**
	 * Returns the theme definition
	 *
	 * @param $id
	 * @return array|null
	 */
	public function find($id)
	{
		return array('id' => $id, 'path' => "/nowhere/$id");
	}
}