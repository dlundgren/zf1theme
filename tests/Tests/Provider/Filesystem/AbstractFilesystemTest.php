<?php
/**
 * Zend Framework 1 Theme Extension tests
 *
 * @author  David Lundgren
 * @license MIT
 */
namespace ZfTheme\Tests\Provider\Filesystem;

use org\bovigo\vfs\vfsStream;

//use ZfTheme\Provider\Filesystem\AbstractFilesystem;

class AbstractFilesystemTest
	extends \PHPUnit_Framework_TestCase
{
	private static $themeDirectories = array(
		'single'   => array(
			'admin'   => array(
				'theme.txt' => "some text"
			),
			'buffalo' => array(
				'theme.json' => '{}'
			)
		),
		'multiple' => array(
			'admin'   => array(
				'theme.txt' => "some text"
			),
			'buffalo' => array(
				'theme.txt' => '{}'
			)
		)
	);

	public static function provideConstructorArguments()
	{
		$root      = vfsStream::create(self::$themeDirectories);
		$single    = $root->getChild('single');
		$singleUrl = $single->url();
		$multi     = $root->getChild('multiple');
		$multiUrl  = $multi->url();

		$singleAdminTheme = array('id' => 'admin', 'path' => $single->getChild('admin')->url());
		$multiAdminTheme  = array('id' => 'admin', 'path' => $multi->getChild('admin')->url());

		$multiBuffaloTheme = array('id' => 'buffalo', 'path' => $multi->getChild('buffalo')->url());

		return array(
			array($singleUrl, 'admin', $singleAdminTheme),
			// FILO order single then multi
			array(array($singleUrl, $multiUrl), 'admin', $singleAdminTheme),
			array(array($multiUrl, $singleUrl), 'admin', $multiAdminTheme),
			// multi buffalot theme regardless of order
			array(array($singleUrl, $multiUrl), 'buffalo', $multiBuffaloTheme),
			array(array($multiUrl, $singleUrl), 'buffalo', $multiBuffaloTheme),
		);
	}

	private function getMockedAbstractFilesystem($args, $useGetDefinitionFilename = true)
	{
		$mock = $this->getMockForAbstractClass(
					 'ZfTheme\DefinitionProvider\Filesystem\AbstractFilesystem', array($args), '', true, true, true, array(
						 'getDefinitionFilename',
						 'decodeFile'
					 ));

		$useGetDefinitionFilename && $mock->expects($this->once())->method('getDefinitionFilename')->will($this->returnValue('theme.txt'));
		$mock->expects($this->once())->method('decodeFile')->will(
			 $this->returnCallback(
				  function ($file) {
					  // not doing anything but our expectation is set that will be called at least once
					  return array();
				  }));

		return $mock;
	}

	/**
	 * @dataProvider provideConstructorArguments
	 */
	public function testConstructorArguments($payload, $theme, $expected)
	{
		$mock = $this->getMockedAbstractFilesystem($payload);

		self::assertEquals($expected, $mock->find($theme));
	}

	public function testAddPath()
	{
		$root      = vfsStream::create(self::$themeDirectories);
		$single    = $root->getChild('single');
		$singleUrl = $single->url();
		$multi     = $root->getChild('multiple');
		$multiUrl  = $multi->url();

		$mock = $this->getMockForAbstractClass(
					 'ZfTheme\DefinitionProvider\Filesystem\AbstractFilesystem', array(null), '', true, true, true, array(
						 'getDefinitionFilename',
						 'decodeFile'
					 ));

		self::assertInstanceOf('ZfTheme\DefinitionProvider\Filesystem\AbstractFilesystem', $mock->addPath($multiUrl));
		$mock->addPath($singleUrl);
		self::assertEquals(array($multiUrl, $singleUrl), $mock->getPaths());
	}

	public function testAddPathThrowsInvalidArgumentException()
	{
		$this->setExpectedException('InvalidArgumentException');

		$mock = $this->getMockForAbstractClass(
					 'ZfTheme\DefinitionProvider\Filesystem\AbstractFilesystem', array(null), '', true, true, true, array(
						 'getDefinitionFilename',
						 'decodeFile'
					 ));
		$mock->addPath(new \stdClass());
	}

	public function testClearPaths()
	{
		$root      = vfsStream::create(self::$themeDirectories);
		$single    = $root->getChild('single');
		$singleUrl = $single->url();
		$multi     = $root->getChild('multiple');
		$multiUrl  = $multi->url();

		$mock = $this->getMockForAbstractClass(
					 'ZfTheme\DefinitionProvider\Filesystem\AbstractFilesystem', array(null), '', true, true, true, array(
						 'getDefinitionFilename',
						 'decodeFile'
					 ));


		$mock->addPath($singleUrl);
		$mock->addPath($multiUrl);
		self::assertInstanceOf('ZfTheme\DefinitionProvider\Filesystem\AbstractFilesystem', $mock->clearPaths());
		self::assertEmpty($mock->getPaths());
	}
}
