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
 * @package ZfTheme\DefinitionProvider\Filesystem
 */
abstract class AbstractFilesystem
	implements DefinitionProvider
{
	/**
	 * @var array The paths to themes
	 */
	private $paths = array();

	/**
	 * Constructor
	 *
	 * Sets the paths if set
	 *
	 * @param array|string $paths
	 */
	public function __construct($paths)
	{
		$paths && $this->setPaths($paths);
	}

	/**
	 * Sets the paths
	 *
	 * Clears the paths first and then adds the paths
	 *
	 * @param array|null $paths
	 * @return AbstractFilesystem
	 */
	public function setPaths($paths)
	{
		$this->paths = (array)$paths;

		return $this;
	}

	/**
	 * Adds the given path(s)
	 *
	 * @param array|string $path
	 * @return AbstractFilesystem
	 * @throws \InvalidArgumentException
	 */
	public function addPath($path)
	{
		if (is_array($path) || is_string($path)) {
			foreach ((array)$path as $p) {
				$this->paths[] = $p;
			}
		}
		else {
			throw new \InvalidArgumentException("addPath takes either an array or string");
		}

		return $this;
	}

	/**
	 * Clears the paths
	 *
	 * @return AbstractFilesystem
	 */
	public function clearPaths()
	{
		$this->paths = array();

		return $this;
	}

	/**
	 * Returns the current paths
	 *
	 * @return array
	 */
	public function getPaths()
	{
		return $this->paths;
	}

	/**
	 * Finds a theme on the paths
	 *
	 * @param string $id
	 * @return array|null The definition when found or null otherwise
	 */
	public function find($id)
	{
		$def = null;
		$defFile = $this->getDefinitionFilename();
		foreach ($this->getPaths() as $path) {
			$file = "$path/$id/$defFile";
			if (file_exists($file)) {
				$def         = (array)$this->decodeFile($file);
				$def['id']   = $id;
				$def['path'] = dirname($file);
				break;
			}
		}

		return $def;
	}

	/**
	 * Returns the definition filename
	 *
	 * This is here mainly for testing purposes
	 *
	 * @seam
	 * @codeCoverageIgnore
	 * @return string
	 */
	protected function getDefinitionFilename()
	{
		return static::DEFINITION_FILENAME;
	}

	/**
	 * Handles the decoding of the file.
	 *
	 * @param $file
	 * @return array
	 */
	abstract protected function decodeFile($file);
}