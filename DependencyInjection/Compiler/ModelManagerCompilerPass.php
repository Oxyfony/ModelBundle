<?php
/**
 * This file is part of the Oxygen Bundle Package.
 *
 * (c) 2014 Oxyfony
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace O2\Bundle\ModelBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Definition;

use Symfony\Component\DependencyInjection\Reference;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use O2\Bundle\ModelBundle\Manager\ManagerBuilder;

/**
 * Etend le bundle OxygenDiffusion
 *
 * @author lolozere
 *
 */
class ModelManagerCompilerPass implements CompilerPassInterface
{
	
	public function process(ContainerBuilder $container)
	{
		// Search tag
		$managers = $container->findTaggedServiceIds('o2_model.manager');
		
		if (count($managers) <= 0)
			return;
		
		$managerBuilder = new ManagerBuilder($container);
		$managerBuilder->completeServices($managers);
	}
}