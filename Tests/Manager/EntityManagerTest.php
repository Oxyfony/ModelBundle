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

use O2\Bundle\ModelBundle\Test\Doctrine\EntityManager as DoctrineEntityManagerTest;
use O2\Bundle\ModelBundle\Entity\Manager\EntityManager as O2EntityManager;
use O2\Bundle\ModelBundle\Manager\ManagerException;

/**
 * Unit test of EntityManager
 *
 * @author Laurent Chedanne <laurent@chedanne.pro>
 *
 */
class EntityManagerTest extends \PHPUnit_Framework_TestCase
{
	
	use DoctrineEntityManagerTest;

	/**
	 * Test constructor failed
	 *
	 * @author Laurent Chedanne <laurent@chedanne.pro>
	 */
	public function testClassDefinition()
	{
		// Entity Manager
		$em = $this->getEntityManager();
		
		// With bundle formed
		$manager = new O2EntityManager('O2ModelTestBundle:ModelExample', 'o2_model.example');
		$this->assertTrue($manager->getAlias() == 'o2_model.example', "EntityManager->getAlias Error");
		$manager->setDoctrineEntityManager($em);
		$this->assertTrue($manager->getClassName() == 'O2\Bundle\ModelBundle\Test\Entity\ModelExample', sprintf("EntityManager->getClassName Error : %s", $manager->getClassName()));
		$this->assertTrue(get_class($manager->getRepository()) == 'Doctrine\ORM\EntityRepository', sprintf("EntityManager->getRepository Error : %s", get_class($manager->getRepository())));
		
		// with class formed
		$manager = new O2EntityManager('O2\Bundle\ModelBundle\Test\Entity\ModelExample', 'o2_model.example');
		$this->assertTrue($manager->getAlias() == 'o2_model.example', "EntityManager->getAlias Error");
		$manager->setDoctrineEntityManager($em);
		$this->assertTrue($manager->getClassName() == 'O2\Bundle\ModelBundle\Test\Entity\ModelExample', sprintf("EntityManager->getClassName Error : %s", $manager->getClassName()));
		$this->assertTrue(get_class($manager->getRepository()) == 'Doctrine\ORM\EntityRepository', sprintf("EntityManager->getRepository Error : %s", get_class($manager->getRepository())));
		
		// with repository
		$manager = new O2EntityManager('O2\Bundle\ModelBundle\Test\Entity\ModelExample', 'o2_model.example', 'O2\Bundle\ModelBundle\Test\Repository\ModelExampleRepository');
		$manager->setDoctrineEntityManager($em);
		$this->assertTrue(get_class($manager->getRepository()) == 'O2\Bundle\ModelBundle\Test\Repository\ModelExampleRepository', sprintf("EntityManager->getRepository Error : %s", get_class($manager->getRepository())));
		
	}
	
	/**
	 *
	 * @depends testClassDefinition
	 */
	public function testPersist() {
		
		// Entity Manager
		$em = $this->getEntityManager();
		$manager = new O2EntityManager('O2ModelTestBundle:ModelExample', 'o2_model.example');
		$manager->setDoctrineEntityManager($em);
		
		// Test a correct persist
		$data = $manager->createInstance();
		$data->setArg1('flush_test');
		$manager->persist($data);
		foreach($manager->getDoctrineEntityManager()->getUnitOfWork()->getScheduledEntityInsertions() as $entity) {
			$this->assertEquals(get_class($entity), $manager->getClassName(), sprintf("Entity %s persist with wrong class (%s)", $manager->getClassName(), get_class($entity)));
		}
		if (count($manager->getDoctrineEntityManager()->getUnitOfWork()->getScheduledEntityInsertions()) <= 0) {
			$this->fail("Persist entity with manager doesn't work");
		}
		// Set for using in next test
		$this->testEntityManager = $manager;
		
		
		// Test a bad persist
		$data = $this->getMock('O2\Bundle\ModelBundle\Test\Entity\ModelExample');
		try {
			$manager->persist($data);
		} catch(ManagerException $e) {
			return;
		}
		$this->fail("Persist must throw an exception if data is not the correct entity to persist");
	}
	
	/**
	 *
	 * @depends testPersist
	 */
	public function testFlush() {
		
		/*
		 * Create row
		 */
		$em = $this->createEntityManager();
		$manager = new O2EntityManager('O2ModelTestBundle:ModelExample', 'o2_model.example');
		$manager->setDoctrineEntityManager($em);
		
		// Persist and flush
		$data = $manager->createInstance();
		$data->setArg1('flush_test');
		$manager->persist($data);
		$manager->flush();
		
		/*
		 * Test existance
		*/
		$em = $this->createEntityManager(false);
		$manager = new O2EntityManager('O2ModelTestBundle:ModelExample', 'o2_model.example');
		$manager->setDoctrineEntityManager($em);
		
		// Search if flushed
		$datas = $manager->getRepository()->findByArg1('flush_test');
		$this->assertEquals(1, count($datas), "Entity doesn't flush");
	}
	
	/**
	 *
	 * @depends testFlush
	 */
	public function testSave() {
	
		/*
		 * Create row
		*/
		$em = $this->createEntityManager();
		$manager = new O2EntityManager('O2ModelTestBundle:ModelExample', 'o2_model.example');
		$manager->setDoctrineEntityManager($em);
	
		// Persist and flush
		$data = $manager->createInstance();
		$data->setArg1('flush_test');
		$manager->save($data);
	
		/*
		 * Test existance
		*/
		$em = $this->createEntityManager(false);
		$manager = new O2EntityManager('O2ModelTestBundle:ModelExample', 'o2_model.example');
		$manager->setDoctrineEntityManager($em);
	
		// Search if flushed
		$datas = $manager->getRepository()->findByArg1('flush_test');
		$this->assertEquals(1, count($datas), "Entity doesn't flush");
	}
	
	/**
	 *
	 * @depends testPersist
	 */
	public function testSaveWithoutFlush() {
	
		/*
		 * Create row
		*/
		$em = $this->createEntityManager();
		$manager = new O2EntityManager('O2ModelTestBundle:ModelExample', 'o2_model.example');
		$manager->setDoctrineEntityManager($em);
	
		// Persist and flush
		$data = $manager->createInstance();
		$data->setArg1('flush_test');
		$manager->save($data, array('flush' => false));
	
		/*
		 * Test existance
		*/
		$em = $this->createEntityManager(false);
		$manager = new O2EntityManager('O2ModelTestBundle:ModelExample', 'o2_model.example');
		$manager->setDoctrineEntityManager($em);
	
		// Search if flushed
		$datas = $manager->getRepository()->findByArg1('flush_test');
		$this->assertEquals(0, count($datas), "Entity mustn't be flushed");
	}
}

