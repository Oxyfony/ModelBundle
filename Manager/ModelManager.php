<?php
namespace O2\Bundle\ModelBundle\Manager;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;

use Symfony\Component\Form\Form;

/**
 * Manager for model class
 *
 * @author Laurent Chedanne <laurent@chedanne.pro>
 *
 */
abstract class ModelManager {
	
	protected $class;
	private $reflectionClass;
	protected $alias;

	/**
	 * Build manager of a model
	 *
	 * @param string $class
	 * @param string $alias
	 * @throws \Exception Class doesn't exist
	 */
	public function __construct($class, $alias) {
		if (!class_exists($class))
			throw new \LogicException(sprintf("%s class doesn't exist", $class));
		
		$this->reflectionClass = new \ReflectionClass($class);
		$this->class = $this->reflectionClass->getName();
		$this->alias = $alias;
	}
	
	/**
	 * Return alias of model
	 *
	 * @return string
	 */
	public function getAlias() {
		return $this->alias;
	}
	/**
	 * Return fully qualified class name of model
	 *
	 * @return string
	 */
	public function getClassName() {
		return $this->class;
	}
	/**
	 * Create instance of a model
	 *
	 * @param mixed $arg1
	 * @param mixed $arg2
	 * @param mixed $argx...
	 */
	public function createInstance() {
		$class = new \ReflectionClass($this->class);
		return $class->newInstanceArgs(func_get_args());
	}

}