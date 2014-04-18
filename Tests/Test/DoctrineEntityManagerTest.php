<?php
namespace O2\Bundle\ModelBundle\Tests\Manager;

use O2\Bundle\ModelBundle\Test\Doctrine\EntityManager;
use O2\Bundle\ModelBundle\Test\Entity\ModelExample;

/**
 * Test the Entity Manager Test trait
 *
 * @author Laurent Chedanne <laurent@chedanne.pro>
 *
 */
class DoctrineEntityManagerTest extends \PHPUnit_Framework_TestCase
{
	
	use EntityManager;

	/**
	 * Test persisting a row
	 *
	 * @author Laurent Chedanne <laurent@chedanne.pro>
	 */
	public function testPersistRow()
	{
		// Entity Manager
		$em = $this->getEntityManager();
		
		$datas = $em->getRepository('O2ModelTestBundle:ModelExample')->findAll();
		$this->assertEquals(0, count($datas), "Test can't work because sqlite database test isn't empty");
		
		// Create
		$example = new ModelExample();
		$em->persist($example);
		$em->flush();
		
		// Test if in database
		$em->clear();
		$datas = $em->getRepository('O2ModelTestBundle:ModelExample')->findAll();
		$this->assertEquals(1, count($datas), "Entity doesn't persist by the test Entity Manager");
	}
}

