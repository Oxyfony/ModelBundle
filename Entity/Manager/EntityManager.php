<?php
/**
 * This file is part of the Oxygen Bundle Package.
 *
 * (c) 2014 Oxyfony
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace O2\Bundle\ModelBundle\Entity\Manager;

use Doctrine\ORM\EntityManager as DoctrineEntityManager;
use Doctrine\ORM\EntityRepository;
use O2\Bundle\ModelBundle\Manager\ModelManager;
use O2\Bundle\ModelBundle\Manager\ManagerException;
use Doctrine\ORM\UnitOfWork;

/**
 * Classe offrant des fonctions de gestion d'une entité
 *
 * @author Laurent Chedanne <laurent@chedanne.pro>
 *
 */
class EntityManager extends ModelManager
{

	/**
	 *
	 * @var DoctrineEntityManager
	 */
	protected $em;

	protected $repository;

	protected $repositoryClass;

	/**
	 *
	 * @var \Doctrine\ORM\Mapping\ClassMetadata
	 */
	protected $meta;

	/**
	 * Build manager of an entity
	 *
	 * @param string $class
	 * @param string $repository
	 *        	(optional) Class repository
	 */
	public function __construct($class, $alias, $repository = null)
	{
		$this->repositoryClass = $repository;
		$this->class = $class;
		$this->alias = $alias;
	}

	/**
	 * Set the Doctrine Entity Manager
	 *
	 * @param DoctrineEntityManager $entityManager
	 */
	public function setDoctrineEntityManager(DoctrineEntityManager $entityManager)
	{
		if (! is_null($this->meta))
			return;
		
		$this->em = $entityManager;
		$this->meta = $this->em->getClassMetadata($this->class);
		
		parent::__construct($this->meta->getName(), $this->alias, $this->repositoryClass);
		
		// Vérification si le repository est un parameter
		if (! is_null($this->repositoryClass)) {
			$this->meta->setCustomRepositoryClass($this->repositoryClass);
			$repositoryClassName = $this->meta->customRepositoryClassName;
			$this->repository = new $repositoryClassName($this->em, $this->meta);
		} else {
			$this->repository = $this->em->getRepository($this->class);
		}
	}
	
	/**
	 * Get doctrine entity manager
	 *
	 * @return DoctrineEntityManager
	 */
	public function getDoctrineEntityManager() {
		return $this->em;
	}

	/**
	 *
	 * @return Doctrine\ORM\Mapping\ClassMetadata
	 */
	public function getMetaData()
	{
		return $this->meta;
	}

	/**
	 *
	 * @return EntityRepository
	 */
	public function getRepository()
	{
		return $this->repository;
	}

	/**
	 *
	 * @see \O2\Bundle\ModelBundle\Manager\ModelManager::save()
	 * @param array $options
	 *        	Options available are :
	 *        	- flush : true by default
	 */
	public function save($models, array $options = array())
	{
		if (!is_array($models)) {
			$models = array($models);
		}
		
		$options = array_merge(array('flush' => true), $options);
		
		// Persist
		$this->persist($models);
		// Flush depends on option value
		if ($options['flush'])
			$this->flush();
	}
	
	/**
	 * Persit a data from the model
	 *
	 * @param object|array $models
	 * @throws ManagerException Data isn't an instance of %s
	 */
	public function persist($models) {
		if (!is_array($models)) {
			$models = array($models);
		}
		
		foreach($models as $model_data) {
			if (is_object($model_data)) {
				if (get_class($model_data) == $this->getClassName()) {
					if ($this->em->getUnitOfWork()->getEntityState($model_data) == UnitOfWork::STATE_NEW) {
						$this->em->persist($model_data);
					}
					continue;
				}
			}
			throw new ManagerException(sprintf("Data isn't an instance of %s", $this->getClassName()));
		}
	}
	
	/**
	 * Flush all data of this entity
	 *
	 */
	public function flush() {
		$entities = array();
		// Persisted
		$entitiesManaged = $this->em->getUnitOfWork()->getScheduledEntityInsertions();
		foreach($entitiesManaged as $entity) {
			if (get_class($entity) == $this->getClassName()) {
				$entities[$this->em->getUnitOfWork()->getSingleIdentifierValue($entity)] = $entity;
			}
		}
		// Updated
		$map = $this->em->getUnitOfWork()->getIdentityMap();
		if (isset($map[$this->getClassName()])) {
			foreach($map[$this->getClassName()] as $entity) {
				$entities[$this->em->getUnitOfWork()->getSingleIdentifierValue($entity)] = $entity;
			}
		}
		// Removed
		$entitiesManaged = $this->em->getUnitOfWork()->getScheduledEntityDeletions();
		foreach($entitiesManaged as $entity) {
			if (get_class($entity) == $this->getClassName()) {
				$identifier = $this->em->getUnitOfWork()->getSingleIdentifierValue($entity);
				if (!array_key_exists($this->em->getUnitOfWork()->getSingleIdentifierValue($entity), $entities)) {
					$entities[$identifier] = $entity;
				}
			}
		}
		$this->em->flush($entities);
	}
}