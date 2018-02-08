<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * The index controller...
 *
 * @version 0.0.1
 * @package App\Controller
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class IndexController extends Controller
{
    /**
     * Indexes.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     *
     * @Route("/", name="index")
     */
    public function index(Request $request)
    {
        return new Response(
            $this->twig->render(
                'base.html.twig'
            )
        );
    }
}

