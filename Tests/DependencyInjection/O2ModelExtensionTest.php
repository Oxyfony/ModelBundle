<?php

namespace O2\Bundle\ModelBundle\Tests\DependencyInjection;

use O2\Bundle\ModelBundle\DependencyInjection\O2ModelExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
/**
 * Unit test for O2ModelExtension class
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class O2ModelExtensionTest extends \PHPUnit_Framework_TestCase
{
	
	private $extension;
	private $container;
	
	protected function setUp()
	{
		$this->extension = new O2ModelExtension();
		$this->container = new ContainerBuilder();
	}
	
	public function testRegister() {
		$this->container->registerExtension($this->extension);
		
		// Later, test configuration tree, ...
	}
	
}
