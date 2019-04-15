<?php

namespace App\Controller;

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
     * Gets home.
     *
     * @return App\HttpKernel\ControllerResult
     *
     * @Route("/", name="get_home", methods={"GET"})
     */
    public function getHome() : ControllerResult
    {
        return new ControllerResult(Response::HTTP_OK);
    }
}
