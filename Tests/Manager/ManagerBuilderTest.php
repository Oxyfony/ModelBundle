<?php
/**
 * This file is part of the Oxygen Bundle Package.
 *
 * (c) 2014 Oxyfony
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace O2\Bundle\ModelBundle\Tests\Manager;

use O2\Bundle\ModelBundle\Manager\ManagerBuilder;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Unit test of ManagerBuilder
 *
 * @author Laurent Chedanne <laurent@chedanne.pro>
 *
 */
class ManagerBuilderTest extends \PHPUnit_Framework_TestCase
{

	/**
	 *
	 * @var ContainerBuilder
	 */
	protected $container;
	
	// use O2\Bundle\ModelBundle\Test\Doctrine\EntityManager;
	public function setUp()
	{
		$this->container = new ContainerBuilder();
		$loader = new Loader\XmlFileLoader($this->container, new FileLocator(__DIR__ . '/../../Test/Resources/config/services'));
		$loader->load('model_example_manager.xml');
		$loader->load('entity_manager.xml');
	}

	/**
	 * Test the definition of the class
	 */
	public function testClassDefinition()
	{
		$managerBuilder = new ManagerBuilder($this->container);
		
		$methods = array('completeServices', 'camelCaseStringToUnderScores');
		foreach ($methods as $method) {
			$this->assertTrue(method_exists($managerBuilder, $method),
				sprintf('Class %s does not have method %s', get_class($managerBuilder), $method));
		}
	}

	/**
	 * Test execution of camelCaseStringToUnderScores
	 *
	 * @author Laurent Chedanne <laurent@chedanne.pro>
	 *         @depends testClassDefinition
	 */
	public function testCamelCaseStringToUnderScores()
	{
		$managerBuilder = new ManagerBuilder($this->container);
		
		$classReflection = new \ReflectionClass($managerBuilder);
		$method = $classReflection->getMethod('camelCaseStringToUnderScores');
		$method->setAccessible(true);
		
		// Test empty value
		$emptiesValues = array('', null);
		foreach ($emptiesValues as $value) {
			$camelDone = $method->invokeArgs($managerBuilder, array($value));
			$this->assertTrue(empty($camelDone),
				sprintf('camelCaseStringToUnderScores returns wrong value (%s) for parameter %s', $camelDone, $value));
		}
		
		// Test real value
		$emptiesValues = array('model' => 'Model', '2_model' => '2Model', 'o2_model' => 'O2Model', 'oxyfony2_model' => 'Oxyfony2Model',
			'model2' => 'Model2');
		foreach ($emptiesValues as $rightReturn => $arg) {
			$camelDone = $method->invokeArgs($managerBuilder, array($arg));
			$this->assertEquals($rightReturn, $camelDone,
				sprintf('camelCaseStringToUnderScores returns wrong value (%s) for parameter %s', $camelDone, $arg));
		}
	}

	/**
	 * Test execution of camelCaseStringToUnderScores
	 *
	 * @author Laurent Chedanne <laurent@chedanne.pro>
	 * @depends testCamelCaseStringToUnderScores
	 */
	public function testCompleteServices()
	{
		$managers = $this->container->findTaggedServiceIds('o2_model.manager');
		$managerBuilder = new ManagerBuilder($this->container);
		
		foreach ($managers as $manager_id => $tags) {
			if (in_array($manager_id, array('o2_model.entity.model_example.manager_empty', 'o2_model.entity.model_example.manager_class_unknow', 'o2_model.entity.model_example.manager_alias_missing'))) {
				$failed = true;
				try {
					$managerBuilder->completeServices(array($manager_id => $tags));
				} catch (\LogicException $e) {
					$failed = false;
				}
				$this->assertFalse($failed, sprintf('LogicException must be thrown for service %s', $manager_id));
			} else {
				$managerBuilder->completeServices(array($manager_id => $tags));
				$manager = $this->container->get($manager_id);
				$this->assertEquals('O2\Bundle\ModelBundle\Test\Entity\ModelExample', $this->container->get($manager_id)->getClassName(),
					sprintf('Error to complete the manager %s for the entity example', $manager_id));
				if (in_array($manager_id, array('o2_model.entity.model_example.manager1', 'o2_model.entity.model_example.manager2'))) {
					$this->assertEquals('o2_model_test.model_example', $this->container->get($manager_id)->getAlias());
				}
				if (in_array($manager_id, array('o2_model.entity.model_example.manager4'))) {
					$this->assertInstanceOf('O2\Bundle\ModelBundle\Test\Repository\ModelExampleRepository', $this->container->get($manager_id)->getRepository());
				}
			}
		}
	}
}

