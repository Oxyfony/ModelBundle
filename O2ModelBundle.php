<?php

namespace O2\Bundle\ModelBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use O2\Bundle\ModelBundle\DependencyInjection\Compiler\ModelManagerCompilerPass;

class O2ModelBundle extends Bundle
{
	
	public function build(ContainerBuilder $container)
	{
		parent::build($container);
		$container->addCompilerPass(new ModelManagerCompilerPass());
	}
	
}
