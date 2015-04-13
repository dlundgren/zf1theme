<?php
/**
 * Zend Framework 1 Theme Extension
 *
 * @author  David Lundgren
 * @license MIT
 */
namespace ZfTheme\DefinitionProvider\Filesystem;

use Symfony\Component\Yaml\Yaml;

/**
 * AbstractFilesystem DefinitionProvider
 *
 * Provides a way to quickly handle multiple filesystem types that allow for differing versions of how theme definitions
 * are stored. We provide YAML and JSON formats, but others may have something else they would like to use.
 *
 * Paths added are checked in FILO order
 *
 * We use Symfony's YAML component because it is supports more of the 1.2 spec than Zend_Config_Yaml
 *
 * @todo    describe the layout
 * @todo wrap the Symfony\Component\Yaml\Exception\ParseException with our own ParseException
 *
 * @package ZfTheme\DefinitionProvider\Filesystem
 */
class ThemeYaml
	extends AbstractFilesystem
{
	/**
	 * @var string the file that defines the theme
	 */
	const DEFINITION_FILENAME = 'theme.yml';

	/**
	 * @var \Symfony\Component\Yaml\Yaml the yaml parser instance
	 */
	private $yamlParser;

	/**
	 * Returns the decoded data
	 *
	 * @param $file
	 * @return array
	 */
	protected function decodeFile($file)
	{
		return $this->getYamlParser()->parse(file_get_contents($file));
	}

	/**
	 * Returns the yaml parser instance
	 *
	 * @return Yaml
	 */
	private function getYamlParser()
	{
		if (!$this->yamlParser) {
			$this->yamlParser = new Yaml();
		}

		return $this->yamlParser;
	}
}