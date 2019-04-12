<?php

namespace App\Controller;

use App\HttpKernel\ControllerResultInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
     * Gets trick comment edit form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick-comment/edit/{id}", name="get_trick_comment_edit_form", methods={"GET"})
     */
    public function getTrickCommentEditForm(Request $request) : ControllerResultInterface
    {
        return $this->getForm($request);
    }

    /**
     * Gets trick comment empty edit form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick-comment/edit", name="get_trick_comment_empty_edit_form", methods={"GET"})
     */
    public function getTrickCommentEmptyEditForm(Request $request) : ControllerResultInterface
    {
        return $this->getForm($request);
    }

    /**
     * Gets trick comment collection.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick-comment", name="get_trick_comment_collection", methods={"GET"})
     */
    public function getTrickCommentCollection(Request $request) : ControllerResultInterface
    {
        return $this->getCollection($request);
    }

    /**
     * Gets trick comment.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick-comment/{id}", name="get_trick_comment", methods={"GET"})
     */
    public function getTrickComment(Request $request) : ControllerResultInterface
    {
        return $this->get($request);
    }

    /**
     * Posts trick comment by edit form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick-comment/edit", name="post_trick_comment_by_edit_form", methods={"POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function postTrickCommentByEditForm(Request $request) : ControllerResultInterface
    {
        return $this->post($request);
    }

    /**
     * Puts trick comment by edit form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick-comment/edit/{id}", name="put_trick_comment_by_edit_form", methods={"PUT"})
     * @Security("has_role('ROLE_USER')")
     */
    public function putTrickCommentByEditForm(Request $request) : ControllerResultInterface
    {
        return $this->put($request);
    }

    /**
     * Deletes trick comment by delete form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick-comment/delete/{id}", name="delete_trick_comment_by_delete_form", methods={"DELETE"})
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteTrickCommentByDeleteForm(Request $request) : ControllerResultInterface
    {
        return $this->delete($request);
    }
}
