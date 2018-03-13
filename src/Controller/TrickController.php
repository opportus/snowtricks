<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
     * Gets trick list.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @Route("/trick", name="get_trick_list")
     * @Method("GET")
     */
    public function getTrickList(Request $request) : Response
    {
        return $this->getList($request);
    }

    /**
     * Gets trick.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @Route("/trick/{slug}", name="get_trick")
     * @Method("GET")
     */
    public function getTrick(Request $request) : Response
    {
        return $this->get($request);
    }

    /**
     * Deletes trick by delete form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @Route("/trick/delete/{slug}", name="delete_trick_by_delete_form")
     * @Method("DELETE")
     * @Security("has_role('ROLE_USER')")
     */
    public function deleteTrickByDeleteForm(Request $request) : Response
    {
        return $this->delete($request);
    }

    /**
     * Gets trick delete form.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     *
     * @Route("/trick/delete/{slug}", name="get_trick_delete_form")
     * @Method("GET")
     * @Security("has_role('ROLE_USER')")
     */
    public function getTrickDeleteForm(Request $request) : Response
    {
        return $this->getForm($request);
    }
}

