<?php

namespace App\Controller;

use App;
use App\HttpKernel\ControllerResult;
use App\View\TwigViewBuilder;
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
     * @App\Configuration\Response(
     *     statusCode=Response::HTTP_OK,
     *     content=@App\Configuration\View(
     *         format="text/html",
     *         builder=TwigViewBuilder::class,
     *         options={
     *             "template"="home/get.html.twig"
     *         }
     *     )
     * )
     */
    public function getHome() : ControllerResult
    {
        return new ControllerResult(Response::HTTP_OK);
    }
}
