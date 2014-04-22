<?php
namespace O2\Bundle\ModelBundle\Tests\Manager;

use O2\Bundle\ModelBundle\O2ModelBundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Unit test of Bundle
 *
 * @author Laurent Chedanne <laurent@chedanne.pro>
 *
 */
class BundleTest extends \PHPUnit_Framework_TestCase
{
	
	/**
	 * Test registering of the bundle
	 *
	 * @author Laurent Chedanne <laurent@chedanne.pro>
	 */
	public function testRegistering()
	{
		$container = new ContainerBuilder();
		
		$bundle = new O2ModelBundle();
		$bundle->boot();
		$bundle->build($container);
		$bundle->shutdown();
	}
}

