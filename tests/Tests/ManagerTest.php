<?php
/**
 * Zend Framework 1 Theme Extension tests
 *
 * @author  David Lundgren
 * @license MIT
 */
namespace ZfTheme\Tests;

use org\bovigo\vfs\vfsStream;
use ZfTheme\DefinitionProvider\PhpArray;
use ZfTheme\GenericTheme;
use ZfTheme\Manager;
use ZfTheme\Test\EchoDefinitionProvider;

class ManagerTest
	extends \PHPUnit_Framework_TestCase
{
	public function testConstructor()
	{
		$provider = new EchoDefinitionProvider();
		$manager  = new Manager($provider);

		self::assertEquals(array($provider), $manager->getProviders());
	}

	public function testSetProvidersThrowsException()
	{
		$provider = new EchoDefinitionProvider();
		$manager = new Manager($provider);

		$this->setExpectedException('InvalidArgumentException');
		
		$manager->setProviders(new \stdClass());
	}

	public function testAddProviderAppendsToTheListOfProviders()
	{
		$provider = new EchoDefinitionProvider();
		$manager  = new Manager($provider);
		$manager->addProvider($provider);
		$manager->addProvider($provider);

		self::assertEquals(array($provider, $provider, $provider), $manager->getProviders());
	}

	public function testZendViewSetGet()
	{
		$view    = new \Zend_View();
		$manager = new Manager(new EchoDefinitionProvider());

		self::assertEquals($view, $manager->setView($view)->getView());
	}

	public function testNewThemeThrowsExceptionWhenNoThemeIsFound()
	{
		$manager = new Manager(new PhpArray(array()));

		$this->setExpectedException("RuntimeException");
		$manager->newTheme('blah');
	}

	public function testNewThemeWithSimpleTheme()
	{
		$themes  = array(
			'admin' => array(
				'id'   => 'admin',
				'path' => 'super',
			)
		);
		$manager = new Manager(new PhpArray($themes));

		$admin = $manager->newTheme('admin');
		self::assertInstanceOf('ZfTheme\GenericTheme', $admin);
		self::assertEquals('admin', $admin->getId());
	}

	public function testNewThemeReturnsCustomThemeClass()
	{
		$class = 'NewThemeCustomClassTest';
		if (!class_exists($class)) {
			$structure = array(
				'adminasdf' => array(
					'theme.php' => '<?php class NewThemeCustomClassTest extends \ZfTheme\GenericTheme {}'
				)
			);
			$vfs       = vfsStream::create($structure);
			$path      = $vfs->getChild('adminasdf')->url();
		}
		else {
			// the class has already been loaded do not try again
			$path = 'super';
		}

		$themes  = array(
			'admin' => array(
				'id'    => 'admin',
				'path'  => $path,
				'class' => $class
			)
		);
		$manager = new Manager(new PhpArray($themes));

		$admin = $manager->newTheme('admin');
		self::assertInstanceOf($class, $admin);
	}

	public function testSharedThemeIsShared()
	{
		$themes = array(
			'generic' => array(
				'id'   => 'generic',
				'path' => 'genx'
			),
			'admin'   => array(
				'id'   => 'admin',
				'path' => 'super',
				'uses' => array('generic')
			)
		);

		$manager = new Manager(new PhpArray($themes));
		$o1      = $manager->sharedTheme('admin');
		$o2      = $manager->sharedTheme('admin');
		$o3      = $manager->newTheme('admin');

		self::assertSame($o1, $o2);
		self::assertNotSame($o1, $o3);
		self::assertNotSame($o2, $o3);
	}

	public function testNewThemeWithExtendedTheme()
	{
		$themes  = array(
			'generic' => array(
				'id'   => 'generic',
				'path' => 'genx'
			),
			'admin'   => array(
				'id'   => 'admin',
				'path' => 'super',
				'uses' => array('generic')
			)
		);
		$manager = new Manager(new PhpArray($themes));
		$admin   = $manager->newTheme('admin');

		$generic = $manager->sharedTheme('generic');
		self::assertEquals('admin', $admin->getId());
		self::assertEquals(array('generic' => $generic), $admin->getParentThemes());
	}

	/**
	 * @todo finish this test --- bad dolph ^ dlundgren
	 */
	public function testThemeRegistersWithView()
	{
		$themes  = array(
			'generic' => array(
				'id'   => 'generic',
				'path' => 'genx'
			),
			'admin'   => array(
				'id'   => 'admin',
				'path' => 'super',
				'uses' => array('generic')
			)
		);
		$manager = new Manager(new PhpArray($themes));
		$admin   = $manager->newTheme('admin');

		$generic = $manager->sharedTheme('generic');
		self::assertEquals('admin', $admin->getId());
		self::assertEquals(array('generic' => $generic), $admin->getParentThemes());
	}
}
