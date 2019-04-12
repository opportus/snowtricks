<?php

namespace App\Controller;

use App\HttpKernel\ControllerResultInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
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
     * @Route("/trick/edit/{slug}", name="get_trick_edit_form", methods={"GET"})
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
     * @Route("/trick/edit", name="get_trick_edit_empty_form", methods={"GET"})
     */
    public function getTrickEditEmptyForm(Request $request) : ControllerResultInterface
    {
        return $this->getForm($request);
    }

    /**
     * Gets trick collection.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick", name="get_trick_collection", methods={"GET"})
     */
    public function getTrickCollection(Request $request) : ControllerResultInterface
    {
        return $this->getCollection($request);
    }

    /**
     * Gets trick.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick/{slug}", name="get_trick", methods={"GET"})
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
     * @Route("/trick/edit", name="post_trick_by_edit_form", methods={"POST"})
     * @Security("has_role('ROLE_USER')")
     */
    public function postTrickByEditForm(Request $request) : ControllerResultInterface
    {
        return $this->post($request);
    }

    /**
     * Puts the trick.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick/edit/{slug}", name="put_trick_by_edit_form", methods={"PUT"})
     * @Security("has_role('ROLE_USER')")
     */
    public function putTrickByEditForm(Request $request) : ControllerResultInterface
    {
        return $this->put($request);
    }

    /**
     * Deletes trick by delete form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick/delete/{slug}", name="delete_trick_by_delete_form", methods={"DELETE"})
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteTrickByDeleteForm(Request $request) : ControllerResultInterface
    {
        return $this->delete($request);
    }
}
