<?php

namespace App\EventListener;

use App\Configuration\AnnotationInterface;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

/**
 * The controller annotation reader listener.
 *
 * @version 0.0.1
 * @package App\EventListener
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class ControllerAnnotationReaderListener
{
    /**
     * @var Doctrine\Common\Annotations\Reader $reader
     */
    private $reader;

    /**
     * Constructs the controller annotation reader listener
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * Reads and loads the controller action annotations into the request attributes.
     * 
     * @param Symfony\Component\HttpKernel\Event\FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();

        if (!\is_array($controller)) {
            return;
        }

        $controllerClassName = \get_class($controller[0]);
        $controllerClassReflection = new \ReflectionClass($controllerClassName);
        $controllerMethodReflection = $controllerClassReflection->getMethod($controller[1]);

        $methodAnnotations = $this->reader->getMethodAnnotations($controllerMethodReflection);

        $supportedAnnotations = [];

        foreach ($methodAnnotations as $annotation) {
            if ($annotation instanceof AnnotationInterface) {
                $supportedAnnotations[$annotation->getAlias()][] = $annotation;
            }
        }

        $request = $event->getRequest();

        foreach ($supportedAnnotations as $alias => $annotations) {
            $request->attributes->set(\sprintf('_%s_configurations', $alias), $annotations);
        }
    }
}
