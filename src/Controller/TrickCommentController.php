<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Entity\TrickComment;
use App\Form\Type\TrickCommentEditType;
use App\Exception\Http\NotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * The trick comment controller...
 *
 * @version 0.0.1
 * @package App\Controller
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class TrickCommentController extends Controller
{
    /**
     * Lists the trick comments.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @Route("/ajax/trick-comment", name="trick_comment_list_ajax")
     * @Method("GET")
     */
    public function list(Request $request) : Response
    {
        try {
            $offset   = ($request->query->getInt('page', 1) - 1) * $this->parameters['list']['per_page'];
            $comments = $this->entityManager->getRepository(TrickComment::class)->findBy(
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

            if  (empty($comments)) {
                throw new NotFoundException();
            }

            $status = 200;

        } catch (\Exception $exception) {
            if ($exception instanceof EntityNotFoundException) {
                $status = 404;

            } else {
                $status = 500;

                $this->logger->critical(
                    sprintf(
                        'A %s has occured during a trick comment list action',
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
        if ($request->attributes->get('_route') === 'trick_comment_list_html') {
            if ($this->translator->getCatalogue()->has('trick_comment.list.notification.' . (string) $status)) {
                $request->getSession()->getFlashBag()->add(
                    $status,
                    $this->translator->trans(
                        'trick_comment.list.notification.' . (string) $status
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
        } elseif ($request->attributes->get('_route') === 'trick_comment_list_ajax') {
            return new JsonResponse(
                array(
                    'html'      => isset($exception) ? null : $this->twig->render(
                        'trick/comment/list.html.twig',
                        array(
                            'comments' => $comments,
                        )
                    ),
                    'exception' => isset($exception) ? (string) $exception : null,
                ),
                $status
            );

        // REST response...
        } elseif ($request->attributes->get('_route') === 'trick_comment_list_rest') {

        }
    }

    /**
     * Edits the trick comment.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @Route("/ajax/trick-comment/edit/{id}", name="trick_comment_edit_ajax", defaults={"id": null})
     * @Method("GET")
     */
    public function edit(Request $request) : Response
    {
        // Operation...
        try {
            if ($this->authorizationChecker->isGranted('ROLE_USER') === false) {
                throw new AccessDeniedException(
                    sprintf(
                        'User %s has not the privilege to edit a trick comment',
                        $this->tokenStorage->getToken()->getUser()->getUsername()
                    )
                );
            }

            // Edits an existing comment...
            if ($request->attributes->getInt('id')) {
                $comment = $this->entityManager->getRepository(TrickComment::class)
                    ->findOneById($request->attributes->getInt('id'))
                ;

                if ($comment === null) {
                    throw new EntityNotFoundException(
                        sprintf(
                            'Trick comment %s not found',
                            $request->attributes->get('id')
                        )
                    );
                }

                if ($comment->getAuthor()->getId() !== $this->tokenStorage->getToken()->getUser()->getId()) {
                    throw new AccessDeniedException(
                        sprintf(
                            'User %s is not the author of the trick comment %s',
                            $this->tokenStorage->getToken()->getUser()->getUsername(),
                            (string) $comment->getId()
                        )
                    );
                }

            // Edits a new comment...
            } else {
                $comment = new TrickComment();

                if ($request->query->get('attribute')['thread']) {
                    $thread = $this->entityManager->getRepository(Trick::class)
                        ->findOneById((int) $request->query->get('attribute')['thread'])
                    ;

                    if ($thread === null) {
                        throw new RequestNotValidException(
                            sprintf(
                                'Thread %s not found',
                                $request->query->get('attribute')['thread']
                            )
                        );
                    }

                    $comment->setThread($thread);

                } else {
                    throw new RequestNotValidException(
                        sprintf(
                            'The query must contain the ID of the trick to which the new comment will be attached'
                        )
                    );
                }

                if ($request->query->get('attribute')['parent']) {
                    $parent = $this->entityManager->getRepository(TrickComment::class)
                        ->findOneById((int) $request->query->get('attribute')['parent'])
                    ;

                    if ($parent === null) {
                        throw new RequestNotValidException(
                            sprintf(
                                'Trick comment parent %s not found',
                                $request->query->get('attribute')['parent']
                            )
                        );
                    }

                    $comment->setParent($parent);
                }
            }

            $form = $this->formFactory->createNamed(
                'trick_comment_edit' . (($comment->getId() === null) ? '' : '_' . (string) $comment->getId()),
                TrickCommentEditType::class,
                $comment,
                array(
                    'action' => $comment->getId() === null
                        ? $this->router->generate(str_replace('edit', 'create', $request->attributes->get('_route')))
                        : $this->router->generate(str_replace('edit', 'update', $request->attributes->get('_route')), array('id' => $comment->getId()))
                    ,
                    'method' => $comment->getId() === null
                        ? 'POST'
                        : 'PATCH'
                    ,
                )
            );

            $status = 200;

        } catch (\Exception $exception) {
            if ($exception instanceof RequestNotValidException) {
                $status = 400;

            } elseif ($exception instanceof AccessDeniedException) {
                $status = 403;

            } elseif ($exception instanceof EntityNotFoundException) {
                $status = 404;

            } else {
                $status = 500;

                $this->logger->critical(
                    sprintf(
                        'A %s has occured during a trick comment edit action',
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
        if ($request->attributes->get('_route') === 'trick_comment_edit_html') {
            if ($this->translator->getCatalogue()->has('trick_comment.list.notification.' . (string) $status)) {
                $request->getSession()->getFlashBag()->add(
                    $status,
                    $this->translator->trans(
                        'trick_comment.edit.notification.' . (string) $status
                    )
                );
            }

            if (isset($this->parameters['edit']['redirection'][$status])) {
                return new RedirectResponse(
                    $this->router->generate(
                        $this->parameters['edit']['redirection'][$status]
                    )
                );

            } else {

            }

        // AJAX response...
        } elseif ($request->attributes->get('_route') === 'trick_comment_edit_ajax') {
            return new JsonResponse(
                array(
                    'html'      => isset($exception) ? null : $this->twig->render(
                        'trick/comment/edit.html.twig',
                        array(
                            'form' => $form->createView(),
                        )
                    ),
                    'exception' => isset($exception) ? (string) $exception : null,
                ),
                $status
            );
        }
    }

    /**
     * Creates the trick comment.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @Route("/ajax/trick-comment", name="trick_comment_create_ajax")
     * @Method("POST")
     */
    public function create(Request $request) : Response
    {
        try {
            if ($this->authorizationChecker->isGranted('ROLE_USER') === false) {
                throw new AccessDeniedException(
                    sprintf(
                        'User %s has not the privilege to create a trick comment',
                        $this->tokenStorage->getToken()->getUser()->getUsername()
                    )
                );
            }

            $comment = new TrickComment();

            $comment->setAuthor(
                $this->tokenStorage->getToken()->getUser()
            );

            $form = $this->formFactory->createNamed(
                'trick_comment_edit',
                TrickCommentEditType::class,
                $comment,
                array(
                    'action' => $this->router->generate($request->attributes->get('_route')),
                    'method' => 'POST',
                )
            );

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->persist($form->getData());
                $this->entityManager->flush();

                $status = 201;

            } else {
                throw new RequestNotValidException(
                    sprintf(
                        '%s',
                        $form->getErrors()
                    )
                );
            }

        } catch (\Exception $exception) {
            if ($exception instanceof RequestNotValidException) {
                $status = 400;

            } elseif ($exception instanceof AccessDeniedException) {
                $status = 403;

            } else {
                $status = 500;

                $this->logger->critical(
                    sprintf(
                        'A %s has occured during a trick comment create action',
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
        if ($request->attributes->get('_route') === 'trick_comment_create_html') {
            if ($this->translator->getCatalogue()->has('trick_comment.create.notification.' . (string) $status)) {
                $request->getSession()->getFlashBag()->add(
                    $status,
                    $this->translator->trans(
                        'trick_comment.create.notification.' . (string) $status
                    )
                );
            }

            if (isset($this->parameters['create']['redirection'][$status])) {
                return new RedirectResponse(
                    $this->router->generate(
                        $this->parameters['create']['redirection'][$status]
                    )
                );

            } else {

            }

        // AJAX response...
        } elseif ($request->attributes->get('_route') === 'trick_comment_create_ajax') {
            return new JsonResponse(
                array(
                    'html'      => isset($exception) ? null : $this->twig->render(
                        'trick/comment/edit.html.twig',
                        array(
                            'form' => $form->createView(),
                        )
                    ),
                    'exception' => isset($exception) ? (string) $exception : null,
                ),
                $status
            );

        // REST response...
        } elseif ($request->attributes->get('_route') === 'trick_comment_create_rest') {

        }
    }

    /**
     * Updates the trick comment.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @Route("/ajax/trick-comment/{id}", name="trick_comment_update_ajax")
     * @Method("PATCH")
     */
    public function update(Request $request) : Response
    {
        try {
            if ($this->authorizationChecker->isGranted('ROLE_USER') === false) {
                throw new AccessDeniedException(
                    sprintf(
                        'User %s has not the privilege to update the trick comment %s',
                        $this->tokenStorage->getToken()->getUser()->getUsername(),
                        $request->attributes->get('id')
                    )
                );
            }

            $comment = $this->entityManager->getRepository(TrickComment::class)
                ->findOneById($request->attributes->getInt('id'))
            ;

            if ($comment === null) {
                throw new EntityNotFoundException(
                    sprintf(
                        'Trick comment %s not found',
                        $request->attributes->get('id')
                    )
                );
            }

            if ($comment->getAuthor()->getId() !== $this->tokenStorage->getToken()->getUser()->getId()) {
                throw new AccessDeniedException(
                    sprintf(
                        'User %s is not the author of the trick comment %s',
                        $this->tokenStorage->getToken()->getUser()->getUsername(),
                        (string) $comment->getId()
                    )
                );
            }

            $form = $this->formFactory->createNamed(
                'trick_comment_edit_' . (string) $comment->getId(),
                TrickCommentEditType::class,
                $comment,
                array(
                    'action' => $this->router->generate($request->attributes->get('_route'), array('id' => (string) $comment->getId())),
                    'method' => 'PATCH',
                )
            );

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->flush();

                $status = 201;

            } else {
                throw new RequestNotValidException(
                    sprintf(
                        '%s',
                        $form->getErrors()
                    )
                );
            }

        } catch (\Exception $exception) {
            if ($exception instanceof RequestNotValidException) {
                $status = 400;

            } elseif ($exception instanceof AccessDeniedException) {
                $status = 403;

            } elseif ($exception instanceof EntityNotFoundException) {
                $status = 404;

            } else {
                $status = 500;

                $this->logger->critical(
                    sprintf(
                        'A %s has occured during a trick comment update action',
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
        if ($request->attributes->get('_route') === 'trick_comment_update_html') {
            if ($this->translator->getCatalogue()->has('trick_comment.update.notification.' . (string) $status)) {
                $request->getSession()->getFlashBag()->add(
                    $status,
                    $this->translator->trans(
                        'trick_comment.update.notification.' . (string) $status
                    )
                );
            }

            if (isset($this->parameters['update']['redirection'][$status])) {
                return new RedirectResponse(
                    $this->router->generate(
                        $this->parameters['update']['redirection'][$status]
                    )
                );

            } else {

            }

        // AJAX response...
        } elseif ($request->attributes->get('_route') === 'trick_comment_update_ajax') {
            return new JsonResponse(
                array(
                    'html'      => isset($exception) ? null : $this->twig->render(
                        'trick/comment/edit.html.twig',
                        array(
                            'form' => $form->createView(),
                        )
                    ),
                    'exception' => isset($exception) ? (string) $exception : null,
                ),
                $status
            );

        // REST response...
        } elseif ($request->attributes->get('_route') === 'trick_comment_update_rest') {

        }
    }

    /**
     * Deletes the trick comment.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @Route("/ajax/trick-comment/{id}", name="trick_comment_delete_ajax")
     * @Method("DELETE")
     */
    public function delete(Request $request) : Response
    {
        try {
            if ($this->authorizationChecker->isGranted('ROLE_USER') === false) {
                throw new AccessDeniedException(
                    sprintf(
                        'User %s has not the privilege to delete the trick comment %s',
                        $this->tokenStorage->getToken()->getUser()->getUsername(),
                        $request->attributes->get('id')
                    )
                );
            }

            if ($this->csrfTokenManager->isTokenValid(new CsrfToken('trick-comment-delete', $request->headers->get('x-csrf-token'))) === false) {
                throw new AccessDeniedException(
                    sprintf(
                        'Token not valid'
                    )
                );
            }

            $comment = $this->entityManager->getRepository(TrickComment::class)
                ->findOneById($request->attributes->getInt('id'))
            ;

            if ($comment === null) {
                throw new EntityNotFoundException(
                    sprintf(
                        'Trick comment %s not found',
                        $request->attributes->get('id')
                    )
                );
            }

            if ($comment->getAuthor()->getId() !== $this->tokenStorage->getToken()->getUser()->getId()) {
                throw new AccessDeniedException(
                    sprintf(
                        'User %s is not the author of the trick comment %s',
                        $this->tokenStorage->getToken()->getUser()->getUsername(),
                        (string) $comment->getId()
                    )
                );
            }

            $this->entityManager->remove($comment);
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
                        'A %s has occured during a trick comment delete action',
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
        if ($request->attributes->get('_route') === 'trick_comment_delete_html') {
            if ($this->translator->getCatalogue()->has('trick_comment.delete.notification.' . (string) $status)) {
                $request->getSession()->getFlashBag()->add(
                    $status,
                    $this->translator->trans(
                        'trick_comment.delete.notification.' . (string) $status
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
                return new Response(null, $status);
            }

        // AJAX response...
        } elseif ($request->attributes->get('_route') === 'trick_comment_delete_ajax') {
            return new JsonResponse(
                array(
                    'html'      => null,
                    'exception' => isset($exception) ? (string) $exception : null,
                ),
                $status
            );

        // REST response...
        } elseif ($request->attributes->get('_route') === 'trick_comment_update_rest') {

        }
    }
}

