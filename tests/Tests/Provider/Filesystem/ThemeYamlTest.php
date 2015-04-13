<?php
/**
 * Zend Framework 1 Theme Extension tests
 *
 * @author  David Lundgren
 * @license MIT
 */
namespace ZfTheme\Tests\DefinitionProvider\Filesystem;

use ZfTheme\DefinitionProvider\Filesystem\ThemeYaml;
use org\bovigo\vfs\vfsStream;

class ThemeYamlTest
	extends \PHPUnit_Framework_TestCase
{
	private static $yamlDecoded = array(
		'super'     => 'duper',
		'something' => array(
			1,
			2
		)
	);
	private static $themeDirectories = array(
		'single'   => array(
			'admin'   => array(
				'theme.txt' => "some text"
			),
			'buffalo' => array(
				'theme.json' => '{"super":"duper","colors":["#f00","#00f"]}'
			),
			'kakaw'   => array(
				// todo a better way of doing this? ^ dlundgren
				'theme.yml' => "super: \"duper\"\nsomething: \n - 1\n - 2\n"
			)
		),
		'multiple' => array(
			'admin'   => array(
				'theme.txt' => "some text"
			),
			'buffalo' => array(
				'theme.txt' => '{}'
			),
			'momo' => array(
				'theme.yml' => "super: \"duper\"\nsomething: \n - 1\n - 2\n'"
			)
		)
	);

	public function testThemeJsonReturnsDefinition()
	{
		$root             = vfsStream::setup('themes', null, self::$themeDirectories);
		$provider         = new ThemeYaml($root->getChild('single')->url());
		$expected         = self::$yamlDecoded;
		$expected['id']   = 'kakaw';
		$expected['path'] = $root->getChild('single')->getChild('kakaw')->url();
		$kakaw            = $provider->find('kakaw');
		self::assertEquals($expected, $kakaw);
	}

	public function testDecodeWithInvalidData()
	{
		$root             = vfsStream::setup('themes', null, self::$themeDirectories);
		$provider         = new ThemeYaml(array($root->getChild('multiple')->url(), $root->getChild('single')->url()));
//		$expected         = self::$yamlDecoded;
//		$expected['id']   = 'momo';
//		$expected['path'] = $root->getChild('single')->getChild('momo')->url();

		$this->setExpectedException("Symfony\\Component\\Yaml\\Exception\\ParseException");
		$momo            = $provider->find('momo');
	}
}