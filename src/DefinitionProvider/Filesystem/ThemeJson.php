<?php
/**
 * Zend Framework 1 Theme Extension
 *
 * @author  David Lundgren
 * @license MIT
 */
namespace ZfTheme\DefinitionProvider\Filesystem;

use ZfTheme\DefinitionProvider\DefinitionProvider;

/**
 * AbstractFilesystem DefinitionProvider
 *
 * Provides a way to quickly handle multiple filesystem types that allow for differing versions of how theme definitions
 * are stored. We provide YAML and JSON formats, but others may have something else they would like to use.
 *
 * Paths added are checked in FILO order
 *
 * @todo describe the JSON layout
 * @todo wrap the Zend_Json_Exception with our own ParseException
 *
 * @package ZfTheme\DefinitionProvider\Filesystem
 */
class ThemeJson
	extends AbstractFilesystem
{
	/**
	 * @var string the file that defines the theme
	 */
	const DEFINITION_FILENAME = 'theme.json';

	/**
	 * Returns the decoded data
	 *
	 * @param $file
	 * @return array
	 */
	protected function decodeFile($file)
	{
		return \Zend_Json::decode(file_get_contents($file));
	}
}