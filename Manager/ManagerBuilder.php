<?php
namespace O2\Bundle\ModelBundle\Manager;

use Symfony\Component\DependencyInjection\ContainerBuilder;
/**
 * Build service for manage a model
 *
 * @author Laurent Chedanne <laurent@chedanne.pro>
 *
 */
class ManagerBuilder {
	
	/**
	 *
	 * @var ContainerBuilder
	 */
	protected $container;
	
	/**
	 * Contructor of ManagerBuilder
	 *
	 * @param ContainerBuilder $container
	 */
	public function __construct(ContainerBuilder $container) {
		$this->container = $container;
	}
	
	/**
	 * Transform string caml case in string with lowercase word seperated by underscore
	 *
	 * @param string $str
	 * @return string
	 */
	protected function camelCaseStringToUnderScores($str) {
		preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $str, $matches);
		$ret = $matches[0];
		foreach ($ret as &$match) {
			$match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
		}
		return implode('_', $ret);
	}
	
	/**
	 * Complete manager services
	 *
	 * @param array $managers Array of attributes about tag o2_model.manager by service id key. Example :
	 * 		array('my_service' => array('type' => 'entity', 'class' => 'MyBundle:Entity'))
	 * @throws \LogicException
	 */
	public function buildServices(array $managers) {
		if (count($managers) <= 0)
			return;
		
		foreach ($managers as $service_id => $attributes) {
			if (empty($attributes['type']))
				$attributes['type'] = 'entity';
				
			if (empty($attributes['class']))
				throw new \LogicException(sprintf("Attribute class missing for tag o2_model.manager in service definition %s", $service_id));
			elseif (strpos($attributes['class'], ':') === false && !class_exists($attributes['class'])) {
				// Test if class exist
				throw new \LogicException(sprintf("Model class %s defined in service definition %s doesn't exist", $attributes['class'], $service_id));
			}
				
			if (empty($attributes['alias'])) {
				// Try to auto generate alias
				if (strpos($attributes['class'], ':') === false) {
					throw new \LogicException(sprintf("If you use fully qualified class name in attribute class, you must declare the alias attribute in service definition %s doesn't exist", $service_id));
				} else {
					$class_parts = explode(':', $attributes['class']);
					$alias = $this->camelCaseStringToUnderScores($class_parts[0]).'.'.$this->camelCaseStringToUnderScores($class_parts[0]);
				}
			}
				
			switch($attributes['type']) {
				case 'entity':
					$this->container->getDefinition($id)->setClass('O2\Bundle\ModelBundle\Entity\Manager\EntityManager');
					$this->container->getDefinition($id)->addArgument($attributes['class']);
					if (!empty($attributes['repository'])) {
						$this->container->getDefinition($id)->addArgument($attributes['repository']);
					}
					$this->container->getDefinition($id)->addMethodCall('setDoctrineEntityManager', new Reference('doctrine.orm.entity_manager'));
					break;
				default:
					throw new \LogicException(sprintf('Model type "%s" has not yet managed', $attributes['type']));
			}
		}
	}
	
}