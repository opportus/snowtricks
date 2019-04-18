<?php

namespace App\DependencyInjection\Compiler;

use App\EventListener\ExceptionHandlerListener;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * The exception handler pass.
 *
 * @version 0.0.1
 * @package App\DependencyInjection\Compiler
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class ExceptionHandlerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $exceptionHandlers = [];

        foreach ($container->findTaggedServiceIds('exception_handler') as $id => $tags) {
            $exceptionHandlers[] = new Reference($id);
        }

        $container->getDefinition(ExceptionHandlerListener::class)
            ->setArguments([$exceptionHandlers])
        ;
    }
}
