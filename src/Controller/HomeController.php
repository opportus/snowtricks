<?php

namespace App\Controller;

use App;
use App\HttpKernel\ControllerResult;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * The home controller.
 *
 * @version 0.0.1
 * @package App\Controller
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
class HomeController
{
    /**
     * Gets the home.
     *
     * @return App\HttpKernel\ControllerResult
     *
     * @Route("/", name="get_home", methods={"GET"})
     * 
     * @App\Annotation\Response(
     *     content=@App\Annotation\View(
     *         format="text/html",
     *         builder="App\View\TwigViewBuilder",
     *         options={
     *             "template"="home/get.html.twig"
     *         }
     *     ),
     *     statusCode=Response::HTTP_OK,
     *     headers={},
     *     options={}
     * )
     */
    public function getHome() : ControllerResult
    {
        return new ControllerResult(Response::HTTP_OK);
    }
}
