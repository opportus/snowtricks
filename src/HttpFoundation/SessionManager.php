<?php

namespace App\HttpFoundation;

use App\HttpKernel\ControllerResultInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * The session manager...
 *
 * @version 0.0.1
 * @package App\HttpKernel
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class SessionManager implements SessionManagerInterface
{
    /**
     * @var array $parameters
     */
    protected $parameters;

    /**
     * @var Symfony\Component\HttpFoundation\Session\SessionInterface $session
     */
    protected $session;

    /**
     * @var Symfony\Component\Translations\TranslatorItnerface $translator
     */
    protected $translator;

    /**
     * Constructs the response factory.
     *
     * @param array $parameters
     * @param Symfony\Component\HttpFoundation\Session\SessionInterface $session
     * @param Symfony\Component\HttpFoundation\Translation\TranslatorInterface $translator
     */
    public function __construct(array $parameters, SessionInterface $session, TranslatorInterface $translator)
    {
        $this->parameters = $parameters;
        $this->session    = $session;
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     */
    public function generateFlash(Request $request, ControllerResultInterface $controllerResult)
    {
        $parameters = $this->resolveParameters($request, $controllerResult);

        if (null === $parameters['flash']['message']['id']) {
            return;
        }

        if ($this->translator->getCatalogue()->has($parameters['flash']['message']['id'])) {
            if ($parameters['flash']['message']['parameters']) {
                $entity = $controllerResult->getData()['entity'];

                foreach ($parameters['flash']['message']['parameters'] as $id => $property) {
                    $parameters['flash']['message']['parameters']['%' . $id . '%'] = $entity->$property();
                    unset($parameters['flash']['message']['parameters'][$id]);
                }
            }

            $message = $this->translator->trans(
                $parameters['flash']['message']['id'],
                $parameters['flash']['message']['parameters'],
                $parameters['flash']['message']['domain'],
                $parameters['flash']['message']['locale']
            );

        } else {
            $message = $parameters['flash']['message']['id'];
        }

        $this->session->getFlashBag()->add(
            $parameters['flash']['code'],
            $message
        );
    }

    /**
     * Resolves the session manager parameters.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @param  App\HttpKernel\ControllerResultInterface $controllerResult
     * @return array
     */
    protected function resolveParameters(Request $request, ControllerResultInterface $controllerResult) : array
    {
        $parameters = array();
        $action     = $request->attributes->get('_controller');
        $status     = $controllerResult->getStatusCode();

        if (isset($this->parameters[$action]['session'])) {
            foreach ($this->parameters[$action]['session'] as $params) {
                if (isset($params['status']) && $params['status'] === $status) {
                    $parameters = $params;

                    break;
                }
            }
        }

        $parametersResolver = new OptionsResolver();
        $parametersResolver->setDefaults(array(
            'status' => null,
            'flash'  => array()
        ));

        $flashParametersResolver = new OptionsResolver();
        $flashParametersResolver->setDefaults(array(
            'code'    => $status,
            'message' => array()
        ));

        $flashMessageParametersResolver = new OptionsResolver();
        $flashMessageParametersResolver->setDefaults(array(
            'id'         => null,
            'parameters' => array(),
            'domain'     => null,
            'locale'     => null
        ));

        $resolvedParameters = $parametersResolver->resolve($parameters);

        $flashParameters = isset($parameters['flash']) ? $parameters['flash'] : array();
        $flashParameters = $flashParametersResolver->resolve($flashParameters);

        $flashMessageParameters = isset($parameters['flash']['message']) ? $parameters['flash']['message'] : array();
        $flashMessageParameters = $flashMessageParametersResolver->resolve($flashMessageParameters);

        $resolvedParameters['flash']            = $flashParameters;
        $resolvedParameters['flash']['message'] = $flashMessageParameters;

        return $resolvedParameters;
    }
}

