<?php

namespace App\Controller;

use App\HttpKernel\ControllerResultInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
     * Get the trick edit form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick/edit/{slug}", name="get_trick_edit_form")
     * @Method("GET")
     */
    public function getTrickEditForm(Request $request) : ControllerResultInterface
    {
        return $this->getForm($request);
    }

    /**
     * Get the trick edit empty form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick/edit", name="get_trick_edit_empty_form")
     * @Method("GET")
     */
    public function getTrickEditEmptyForm(Request $request) : ControllerResultInterface
    {
        return $this->getForm($request);
    }

    /**
     * Gets trick list.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick", name="get_trick_list")
     * @Method("GET")
     */
    public function getTrickList(Request $request) : ControllerResultInterface
    {
        return $this->getList($request);
    }

    /**
     * Gets trick.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick/{slug}", name="get_trick")
     * @Method("GET")
     */
    public function getTrick(Request $request) : ControllerResultInterface
    {
        return $this->get($request);
    }

    /**
     * Posts the trick.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick/edit", name="post_trick_by_edit_form")
     * @Method("POST")
     * @Security("has_role('ROLE_USER')")
     */
    public function postTrickByEditForm(Request $request) : ControllerResultInterface
    {
        return $this->post($request);
    }

    /**
     * Patches the trick.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick/edit/{slug}", name="patch_trick_by_edit_form")
     * @Method("PATCH")
     * @Security("has_role('ROLE_USER')")
     */
    public function patchTrickByEditForm(Request $request) : ControllerResultInterface
    {
        return $this->patch($request);
    }

    /**
     * Deletes trick by delete form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick/delete/{slug}", name="delete_trick_by_delete_form")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteTrickByDeleteForm(Request $request) : ControllerResultInterface
    {
        return $this->delete($request);
    }
}

