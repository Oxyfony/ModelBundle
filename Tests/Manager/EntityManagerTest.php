<?php
namespace O2\Bundle\ModelBundle\Tests\Manager;

use O2\Bundle\ModelBundle\Test\Doctrine\EntityManager;
use O2\Bundle\ModelBundle\Entity\Manager\EntityManager as O2EntityManager;

/**
 * Unit test of EntityManager
 *
 * @author Laurent Chedanne <laurent@chedanne.pro>
 *
 */
class EntityManagerTest extends \PHPUnit_Framework_TestCase
{
	
	use EntityManager;

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
}

