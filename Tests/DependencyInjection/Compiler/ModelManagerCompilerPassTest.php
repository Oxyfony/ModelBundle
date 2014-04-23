<?php
/**
 * This file is part of the Oxygen Bundle Package.
 *
 * (c) 2014 Oxyfony
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace O2\Bundle\ModelBundle\Tests\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use O2\Bundle\ModelBundle\DependencyInjection\Compiler\ModelManagerCompilerPass;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;

/**
 * Unit test for O2ModelExtension class
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 *
 * @author Laurent Chedanne <laurent@chedanne.pro>
 *
 */
class ModelManagerCompilerPassTest extends \PHPUnit_Framework_TestCase
{
	
	/**
	 *
	 * @var ContainerBuilder
	 */
	protected $container;
	
	public function setUp()
	{
		$this->container = new ContainerBuilder();
		$loader = new XmlFileLoader($this->container, new FileLocator(__DIR__ . '/../../../Test/Resources/config/services'));
		$loader->load('compiler_tag_test_case.xml');
	}
	
	public function testRegister() {
		$compiler = new ModelManagerCompilerPass();
		$compiler->process($this->container);
		
		$total_services = 0;
		foreach($this->container->getServiceIds() as $service_id) {
			if (preg_match('/o2_model\.entity/', $service_id) == 0)
				continue;
			$total_services++;
		}
		
		$this->assertEquals(1, $total_services, "Wrong number of manager for ModelExample");
	}
	
}
