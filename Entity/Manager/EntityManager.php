<?php
namespace O2\Bundle\ModelBundle\Entity\Manager;

use Doctrine\ORM\EntityManager as DoctrineEntityManager;
use Doctrine\ORM\EntityRepository;

use O2\Bundle\ModelBundle\Manager\ModelManager;

/**
 * Classe offrant des fonctions de gestion d'une entité
 *
 * @author Laurent Chedanne <laurent@chedanne.pro>
 *
 */
class EntityManager extends ModelManager {
	
	/**
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
	 * @param string $repository (optional) Class repository
	 */
	public function __construct($class, $alias, $repository = null) {
		$this->repositoryClass = $repository;
		$this->class = $class;
		$this->alias = $alias;
	}

	/**
	 * Set the Doctrine Entity Manager
	 *
	 * @param DoctrineEntityManager $entityManager
	 */
	public function setDoctrineEntityManager(DoctrineEntityManager $entityManager) {
		if (!is_null($this->meta))
			return;
		
		$this->em = $entityManager;
		$this->meta = $this->em->getClassMetadata($this->class);
		
		parent::__construct($this->meta->getName(), $this->alias, $this->repositoryClass);
		
		//Vérification si le repository est un parameter
		if (!is_null($this->repositoryClass)) {
			$this->meta->setCustomRepositoryClass($this->repositoryClass);
			$repositoryClassName = $this->meta->customRepositoryClassName;
			$this->repository = new $repositoryClassName($this->em, $this->meta);
		} else {
			$this->repository = $this->em->getRepository($this->class);
		}
	}
	/**
	 * @return Doctrine\ORM\Mapping\ClassMetadata
	 */
	public function getMetaData() {
		return $this->meta;
	}

	/**
	 * @return EntityRepository
	 */
	public function getRepository() {
		return $this->repository;
	}
}