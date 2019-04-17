<?php

namespace App\DependencyInjection\Compiler;

use App\HttpFoundation\ResponseBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * The view builder pass.
 *
 * @version 0.0.1
 * @package App\DependencyInjection\Compiler
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class ViewBuilderPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $viewBuilders = [];

        foreach ($container->findTaggedServiceIds('view_builder') as $id => $tags) {
            $viewBuilders[$id] = new Reference($id);
        }

        $container->getDefinition(ResponseBuilder::class)
            ->setArgument('$viewBuilders', $viewBuilders)
        ;
    }
}
