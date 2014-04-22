<?php
namespace O2\Bundle\ModelBundle\Test\Doctrine;

use Doctrine\ORM\EntityManager as DoctrineEntityManager;
use Doctrine\Common\EventManager;
use Doctrine\ORM\Configuration;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\Common\Persistence\Mapping\RuntimeReflectionService;

/**
 * Factory for service doctrine.orm.entity_manager
 *
 * @author Laurent Chedanne <laurent@chedanne.pro>
 *
 */
class EntityManagerFactory
{
	
	public function get()
	{
		// Bundle namespace
		$bundleNamespace = explode('\\', __NAMESPACE__);
		array_pop($bundleNamespace); array_pop($bundleNamespace);
		$bundleNamespace = join('\\', $bundleNamespace);
		
		// Event manager used to create schema before tests
		$eventManager = new EventManager();
		//$eventManager->addEventListener(array("preTestSetUp"), new SchemaSetupListener());
		
		// doctrine xml configs and namespaces of entities
		$prefixList = array(); $aliasNsList = array();
		$ormXmlFolders = array(
			'O2ModelBundle' => array('path' => __DIR__.'/../../Resources/config/doctrine', 'ns' => $bundleNamespace . '\Entity'),
			'O2ModelTestBundle' => array('path' => __DIR__.'/../Resources/config/doctrine', 'ns' => $bundleNamespace . '\Test\Entity')
		);
		
		foreach($ormXmlFolders as $alias => $ns) {
			if (is_dir($ns['path'])) {
				$prefixList[$ns['path']] = $ns['ns'];
				$aliasNsList[$alias] = $ns['ns'];
			}
		}
		
		// create drivers (that reads xml configs)
		$driver = new \Doctrine\ORM\Mapping\Driver\SimplifiedXmlDriver($prefixList);
		
		// create config object
		$config = new Configuration();
		$config->setMetadataCacheImpl(new ArrayCache());
		$config->setMetadataDriverImpl($driver);
		$config->setProxyDir(__DIR__.'/TestProxies');
		$config->setProxyNamespace('O2\Bundle\ModelBundle\Tests\TestProxies');
		$config->setAutoGenerateProxyClasses(true);
		$config->setEntityNamespaces($aliasNsList);
			
		// create entity manager
		$em = DoctrineEntityManager::create(
			array(
				'driver' => 'pdo_sqlite',
				'path' => sys_get_temp_dir() . DIRECTORY_SEPARATOR . "o2-model-test.db"
			),
			$config,
			$eventManager
		);
		return $em;
	}
}