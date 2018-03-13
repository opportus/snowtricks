<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
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
     * Gets trick comment list.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @Route("/trick-comment", name="get_trick_comment_list")
     * @Method("GET")
     */
    public function getTrickCommentList(Request $request) : Response
    {
        return $this->getList($request);
    }

    /**
     * Gets trick comment.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @Route("/trick-comment/{id}", name="get_trick_comment")
     * @Method("GET")
     */
    public function getTrickComment(Request $request) : Response
    {
        return $this->get($request);
    }

    /**
     * Posts trick comment by edit form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @Route("/trick-comment/edit", name="post_trick_comment_by_edit_form")
     * @Method("POST")
     * @Security("has_role('ROLE_USER')")
     */
    public function postTrickCommentByEditForm(Request $request) : Response
    {
        return $this->post($request);
    }

    /**
     * Puts trick comment by edit form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @Route("/trick-comment/edit/{id}", name="put_trick_comment_by_edit_form")
     * @Method("PUT")
     * @Security("has_role('ROLE_USER')")
     */
    public function putTrickCommentByEditForm(Request $request) : Response
    {
        return $this->put($request);
    }

    /**
     * Deletes trick comment by delete form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @Route("/trick-comment/delete/{id}", name="delete_trick_comment_by_delete_form")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteTrickCommentByDeleteForm(Request $request) : Response
    {
        return $this->delete($request);
    }

    /**
     * Gets trick comment edit form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @Route("/trick-comment/edit/{id}", name="get_trick_comment_edit_form")
     * @Method("GET")
     */
    public function getTrickCommentEditForm(Request $request) : Response
    {
        return $this->getForm($request);
    }

    /**
     * Gets trick comment empty edit form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @Route("/trick-comment/edit", name="get_trick_comment_emtpy_edit_form")
     * @Method("GET")
     */
    public function getTrickCommentEmptyEditForm(Request $request) : Response
    {
        return $this->getEmptyForm($request);
    }

    /**
     * Gets trick comment delete form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @Route("/trick-comment/delete/{id}", name="get_trick_comment_delete_form")
     * @Method("GET")
     */
    public function getTrickCommentDeleteForm(Request $request) : Response
    {
        return $this->getForm($request);
    }
}

