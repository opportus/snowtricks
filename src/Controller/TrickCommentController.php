<?php

namespace App\Controller;

use App\Entity\TrickComment;
use App\Form\Type\TrickCommentType;
use App\HttpKernel\ControllerResult;
use App\HttpKernel\ControllerResultInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
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
     * Gets the trick comment list.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick-comment", name="trick_comment_get_list")
     * @Method("GET")
     */
    public function getList(Request $request) : ControllerResultInterface
    {
        $limit    = 5;
        $offset   = ($request->query->getInt('page', 1) - 1) * $limit;
        $order    = $request->query->get('order', array());
        $criteria = array_map(
            function ($value) {
                if ($value === '') {
                    return null;

                } else {
                    return $value;
                }
            },
            $request->query->get('attribute', array())
        );

        $comments = $this->entityManager->getRepository(TrickComment::class)->findBy(
            $criteria,
            $order,
            $limit,
            $offset
        );

        if (empty($comments)) {
            return new ControllerResult(404);
        }

        $data = array(
            'comments' => $comments,
        );

        return new ControllerResult(200, $data);
    }

    /**
     * Gets the trick comment.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick-comment/{id}", name="trick_comment_get")
     * @Method("GET")
     */
    public function get(Request $request) : ControllerResultInterface
    {
        $comment = $this->entityManager->getRepository(TrickComment::class)
            ->findOneById($request->attributes->getInt('id'))
        ;

        if ($comment === null) {
            return new ControllerResult(404);
        }

        $data = array(
            'comment' => $comment,
        );

        return new ControllerResult(200, $data);
    }

    /**
     * Posts the trick comment.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick-comment", name="trick_comment_post")
     * @Method("POST")
     */
    public function post(Request $request) : ControllerResultInterface
    {
        $comment = new TrickComment();
        $form    = $this->formFactory->create(
            TrickCommentType::class,
            $comment,
            $this->parameters[$request->attributes->get('_route')]['form_options']
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($comment);
            $this->entityManager->flush();

            $data = array(
                'comment' => $comment,
            );

            return new ControllerResult(201, $data);

        } elseif ($form->isSubmitted()) {
            $data = array(
                'form' => $form->createView(),
            );

            return new ControllerResult(400, $data);
        }
    }

    /**
     * Puts the trick comment.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick-comment/{id}", name="trick_comment_put")
     * @Method("PUT")
     */
    public function put(Request $request) : ControllerResultInterface
    {
        $comment = $this->entityManager->getRepository(TrickComment::class)
            ->findOneById($request->attributes->getInt('id'))
        ;

        if ($comment === null) {
            return new ControllerResult(404);
        }

        $form = $this->formFactory->create(
            TrickCommentType::class,
            $comment,
            $this->parameters[$request->attributes->get('_route')]['form_options']
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            $data = array(
                'comment' => $comment,
            );

            return new ControllerResult(204, $data);

        } elseif ($form->isSubmitted()) {
            $data = array(
                'form' => $form->createView(),
            );

            return new ControllerResult(400, $data);
        }
    }

    /**
     * Deletes the trick comment.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick-comment/{id}", name="trick_comment_delete")
     * @Method("DELETE")
     */
    public function delete(Request $request) : ControllerResultInterface
    {
        $comment = $this->entityManager->getRepository(TrickComment::class)
            ->findOneById($request->attributes->getInt('id'))
        ;

        if ($comment === null) {
            return new ControllerResult(404);
        }

        $form = $this->formFactory->create(
            TrickCommentType::class,
            $comment,
            $this->parameters[$request->attributes->get('_route')]['form_options']
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->remove($comment);
            $this->entityManager->flush();

            $data = array(
                'comment' => $comment,
            );

            return new ControllerResult(204, $data);

        } elseif ($form->isSubmitted()) {
            $data = array(
                'form' => $form->createView(),
            );

            return new ControllerResult(400, $data);
        }
    }

    /**
     * Gets the trick comment form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick-comment/form/{id}", name="trick_comment_get_form")
     * @Method("GET")
     */
    public function getForm(Request $request) : ControllerResultInterface
    {
        $comment = $this->entityManager->getRepository(TrickComment::class)
            ->findOneById($request->attributes->getInt('id'))
        ;

        if ($comment === null) {
            return new ControllerResult(404);
        }

        $form = $this->formFactory->create(
            TrickCommentType::class,
            $comment,
            $this->parameters['trick_comment_put']['form_options']
        );

        $data = array(
            'form' => $form->createView(),
        );

        return new ControllerResult(200, $data);
    }

    /**
     * Gets the trick comment new form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick-comment/form", name="trick_comment_get_new_form")
     * @Method("GET")
     */
    public function getNewForm(Request $request) : ControllerResultInterface
    {
        $comment = new TrickComment();
        $form    = $this->formFactory->create(
            TrickCommentType::class,
            $comment,
            $this->parameters['trick_comment_post']['form_options']
        );

        $form->submit(array(
            'thread' => $request->query->get('attribute')['thread'],
            'parent' => $request->query->get('attribute', '')['parent'],
        ));

        $data = array(
            'form' => $form->createView(),
        );

        return new ControllerResult(200, $data);
    }
}

