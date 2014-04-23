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

/**
 * Unit test of ModelManager
 *
 * @author Laurent Chedanne <laurent@chedanne.pro>
 *
 */
class ModelManagerTest extends \PHPUnit_Framework_TestCase
{
	
	/**
	 * Test constructor failed
	 *
	 * @author Laurent Chedanne <laurent@chedanne.pro>
	 */
	public function testConstructorFailed()
	{
		// Exception test
		try {
			$manager = $this->getMockForAbstractClass('O2\Bundle\ModelBundle\Manager\ModelManager', array('ModelExample', 'o2_model.example'));
		} catch (\LogicException $e) {
			return;
		}
		$this->fail("Exception expected because ModelExample unknown");
	}

	/**
	 * Test constructor and getClassName
	 *
	 * @author Laurent Chedanne <laurent@chedanne.pro>
	 */
	public function testConstructor()
	{
		// getClassName
		$manager = $this->getMockForAbstractClass('O2\Bundle\ModelBundle\Manager\ModelManager', array('O2\Bundle\ModelBundle\Test\Model\ModelExample', 'o2_model.example'));
		$this->assertTrue($manager->getClassName() == 'O2\Bundle\ModelBundle\Test\Model\ModelExample', "ModelManager->getClassName Error");
		$this->assertTrue($manager->getAlias() == 'o2_model.example', "ModelManager->getAlias Error");
	}
	
	/**
	 * Test create instance
	 *
	 * @author Laurent Chedanne <laurent@chedanne.pro>
	 */
	public function testCreateInstance()
	{
		$manager = $this->getMockForAbstractClass('O2\Bundle\ModelBundle\Manager\ModelManager', array('O2\Bundle\ModelBundle\Test\Model\ModelExample', 'o2_model.example'));
		$i1 = $manager->createInstance();
		$this->assertNull($i1->getArg1());
		$i2 = $manager->createInstance('test');
		$this->assertEquals('test', $i2->getArg1(), "Error to build model with arguments in ModelManager::createInstance");
	}
}

