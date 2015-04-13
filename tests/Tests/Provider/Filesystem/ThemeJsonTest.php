<?php
/**
 * Zend Framework 1 Theme Extension tests
 *
 * @author  David Lundgren
 * @license MIT
 */
namespace ZfTheme\Tests\DefinitionProvider\Filesystem;

use ZfTheme\DefinitionProvider\Filesystem\ThemeJson;
use org\bovigo\vfs\vfsStream;

class ThemeJsonTest
	extends \PHPUnit_Framework_TestCase
{
	private static $themeDirectories = array(
		'single'   => array(
			'admin'   => array(
				'theme.txt' => "some text"
			),
			'buffalo' => array(
				'theme.json' => '{"super":"duper","colors":["#f00","#00f"]}'
			)
		),
		'multiple' => array(
			'admin'   => array(
				'theme.txt' => "some text"
			),
			'buffalo' => array(
				'theme.json' => '"{}asdf'
			)
		)
	);

	public function testThemeJsonReturnsDefinition()
	{
		$root             = vfsStream::setup('themes', null, self::$themeDirectories);
		$provider         = new ThemeJson($root->getChild('single')->url());
		$expected         = json_decode(self::$themeDirectories['single']['buffalo']['theme.json'], JSON_OBJECT_AS_ARRAY);
		$expected['id']   = 'buffalo';
		$expected['path'] = $root->getChild('single')->getChild('buffalo')->url();
		$buffalo          = $provider->find('buffalo');
		self::assertArrayHasKey('super', $buffalo);
		self::assertEquals($expected, $buffalo);
	}

	public function testDecodeWithInvalidData()
	{
		$root             = vfsStream::setup('themes', null, self::$themeDirectories);
		$provider         = new ThemeJson(array($root->getChild('multiple')->url(), $root->getChild('single')->url()));
		$expected         = json_decode(self::$themeDirectories['single']['buffalo']['theme.json'], JSON_OBJECT_AS_ARRAY);
		$expected['id']   = 'buffalo';
		$expected['path'] = $root->getChild('single')->getChild('buffalo')->url();

		$this->setExpectedException('Zend_Json_Exception');
		$buffalo          = $provider->find('buffalo');
	}
}