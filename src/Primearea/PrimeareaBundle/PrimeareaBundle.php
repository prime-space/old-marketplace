<?php

namespace Primearea\PrimeareaBundle;

use Primearea\PrimeareaBundle\CompilerPass\AggregatorCompilerPass;
use Primearea\PrimeareaBundle\CompilerPass\ExecutorCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class PrimeareaBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container
            ->addCompilerPass(new ExecutorCompilerPass())
            ->addCompilerPass(new AggregatorCompilerPass());
    }
}
