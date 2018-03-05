<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\TrickComment;
use App\Form\Type\TrickCommentEditType;
use App\Exception\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * The trick controller...
 *
 * @version 0.0.1
 * @package App\Controller
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickController extends Controller
{
    /**
     * Lists the tricks.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @Route("/ajax/trick", name="trick_list_ajax")
     * @Method("GET")
     */
    public function list(Request $request) : Response
    {
        // Operation...
        try {
            $offset = ($request->query->getInt('page', 1) - 1) * $this->parameters['list']['per_page'];
            $tricks = $this->entityManager->getRepository(Trick::class)->findBy(
                array_map(function ($value) {
                    if ($value === '') {
                        return null;

                    } else {
                        return $value;
                    }

                }, $request->query->get('attribute', array())),
                $request->query->get('order', array()),
                $this->parameters['list']['per_page'],
                $offset
            );

            if  (empty($tricks)) {
                throw new EntityNotFoundException(
                    sprintf(
                        'None of the tricks match the query'
                    )
                );
            }

            $status = 200;

        } catch (\Exception $exception) {
            if ($exception instanceof EntityNotFoundException) {
                $status = 404;

            } else {
                $status = 500;

                $this->logger->critical(
                    sprintf(
                        'A %s has occured during a trick list action',
                        get_class($exception)
                    ),
                    array(
                        'exception' => $exception,
                        'request'   => $request,
                    )
                );
            }
        }

        // HTML response...
        if ($request->attributes->get('_route') === 'trick_list_html') {
            if ($this->translator->getCatalogue()->has('trick.list.notification.' . (string) $status)) {
                $request->getSession()->getFlashBag()->add(
                    $status,
                    $this->translator->trans(
                        'trick.list.notification.' . (string) $status
                    )
                );
            }

            if (isset($this->parameters['list']['redirection'][$status])) {
                return new RedirectResponse(
                    $this->router->generate(
                        $this->parameters['list']['redirection'][$status]
                    )
                );

            } else {

            }

        // AJAX response...
        } elseif ($request->attributes->get('_route') === 'trick_list_ajax') {
            return new JsonResponse(
                array(
                    'html'      => isset($exception) ? null : $this->twig->render(
                        'trick/list.html.twig',
                        array(
                            'tricks' => $tricks,
                        )
                    ),
                    'exception' => isset($exception) ? (string) $exception : null,
                ),
                $status
            );

        // REST response...
        } elseif ($request->attributes->get('_route') === 'trick_list_rest') {

        }
    }

    /**
     * Shows the trick.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @Route("/trick/{slug}", name="trick_show")
     * @Method("GET")
     */
    public function show(Request $request) : Response
    {
        try {
            $trick = $this->entityManager->getRepository(Trick::class)->findOneBySlug(
                $request->attributes->get('slug')
            );

            if  ($trick === null) {
                throw new EntityNotFoundException(
                    sprintf(
                        'Trick %s not found',
                        $request->attributes->get('slug')
                    )
                );
            }

            if ($this->authorizationChecker->isGranted('ROLE_USER')) {
                $comment = new TrickComment();

                $comment->setThread($trick);

                $commentForm = $this->formFactory->create(
                    TrickCommentEditType::class,
                    $comment,
                    array(
                        'action' => $this->router->generate('trick_comment_create_ajax'),
                        'method' => 'POST',
                    )
                );
            }

            $status = 200;

        } catch (\Exception $exception) {
            if ($exception instanceof EntityNotFoundException) {
                $status = 404;

            } else {
                $status = 500;

                $this->logger->critical(
                    sprintf(
                        'A %s has occured during a trick show action',
                        get_class($exception)
                    ),
                    array(
                        'exception' => $exception,
                        'request'   => $request,
                    )
                );
            }
        }

        if ($this->translator->getCatalogue()->has('trick.show.notification.' . (string) $status)) {
            $request->getSession()->getFlashBag()->add(
                $status,
                $this->translator->trans(
                    'trick.show.notification.' . (string) $status
                )
            );
        }

        if (isset($this->parameters['show']['redirection'][$status])) {
            return new RedirectResponse(
                $this->router->generate(
                    $this->parameters['show']['redirection'][$status]
                )
            );

        } else {
            return new Response(
                $this->twig->render(
                    'trick/show.html.twig',
                    array(
                        'trick'       => $trick,
                        'commentForm' => isset($commentForm) ? $commentForm->createView() : null,
                    )
                ),
                $status
            );
        }
    }

    /**
     * Deletes the trick.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @Route("/trick/{id}", name="trick_delete")
     * @Method("DELETE")
     */
    public function delete(Request $request) : Response
    {
        try {
            if ($this->authorizationChecker->isGranted('ROLE_USER') === false ||
                $this->csrfTokenManager->isTokenValid(new CsrfToken('trick-delete', $request->headers->get('x-csrf-token'))) === false
            ) {
                throw new AccessDeniedException();
            }

            $trick = $this->entityManager->getRepository(Trick::class)
                ->findOneById($request->attributes->getInt('id'))
            ;

            if ($trick === null) {
                throw new EntityNotFoundException();
            }

            if ($trick->isAuthor($this->tokenStorage->getToken()->getUser()) === false) {
                throw new AccessDeniedException();
            }

            $this->entityManager->remove($trick);
            $this->entityManager->flush();

            $status = 204;

        } catch (\Exception $exception) {
            if ($exception instanceof AccessDeniedException) {
                $status = 403;

            } elseif ($exception instanceof EntityNotFoundException) {
                $status = 404;

            } else {
                $status = 500;

                $this->logger->critical(
                    sprintf(
                        'A %s has occured during a trick delete action',
                        get_class($exception)
                    ),
                    array(
                        'exception' => $exception,
                        'request'   => $request,
                    )
                );
            }
        }

        if ($this->translator->getCatalogue()->has('trick.delete.notification.' . (string) $status)) {
            $request->getSession()->getFlashBag()->add(
                $status,
                $this->translator->trans(
                    'trick.delete.notification.' . (string) $status
                )
            );
        }

        if (isset($this->parameters['delete']['redirection'][$status])) {
            return new RedirectResponse(
                $this->router->generate(
                    $this->parameters['delete']['redirection'][$status]
                )
            );

        } else {
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(
                    array(),
                    $status
                );

            } else {
                return new Response(
                    null,
                    $status
                );
            }
        }
    }
}

