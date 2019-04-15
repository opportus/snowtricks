<?php

namespace App\HttpKernel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The exception handler interface.
 *
 * @version 0.0.1
 * @package App\HttpKernel
 * @author  Clément Cazaud <opportus@gmail.com>
 * @license https://github.com/opportus/snowtricks/blob/master/LICENSE.md MIT
 */
interface ExceptionHandlerInterface
{
    /**
     * Handles.
     *
     * @param Symfony\Component\HttpFoundation\Request $request
     * @return Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request) : Response;

    /**
     * Checks whether this handler supports the given exception.
     *
     * @param \Exception $exception
     * @return bool
     */
    public function supports(\Exception $exception) : bool;
}
