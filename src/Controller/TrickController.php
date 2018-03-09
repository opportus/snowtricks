<?php

namespace App\Controller;

use App\Entity\Trick;
use App\HttpKernel\ControllerResult;
use App\HttpKernel\ControllerResultInterface;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

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
     * Gets the trick list.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick", name="trick_get_list")
     * @Method("GET")
     */
    public function getList(Request $request) : ControllerResultInterface
    {
        $limit    = 10;
        $offset   = ($request->query->getInt('page', 1) - 1) * $limit;
        $order    = $request->query->get('order', array());
        $criteria = array_map(
            function ($value) {
                if ($value === '') {
                    return null;

                } else {
                    return $value;
                }
            },
            $request->query->get('attribute', array())
        );

        $tricks = $this->entityManager->getRepository(Trick::class)->findBy(
            $criteria,
            $order,
            $limit,
            $offset
        );

        if (empty($tricks)) {
            return new ControllerResult(404);
        }

        $data = array(
            'tricks' => $tricks,
        );

        return new ControllerResult(200, $data);
    }

    /**
     * Gets the trick.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick/{slug}", name="trick_get")
     * @Method("GET")
     */
    public function get(Request $request) : ControllerResultInterface
    {
        $trick = $this->entityManager->getRepository(Trick::class)
            ->findOneBySlug($request->attributes->get('slug'))
        ;

        if ($trick === null) {
            return new ControllerResult(404);
        }

        $data = array(
            'trick' => $trick,
        );

        return new ControllerResult(200, $data);
    }

    /**
     * Deletes the trick.
     *
     * @param  Symfony\Component\HttpFoundation\Request $request
     * @return App\HttpKernel\ControllerResultInterface
     *
     * @Route("/trick/{slug}", name="trick_delete")
     * @Method("DELETE")
     */
    public function delete(Request $request) : ControllerResultInterface
    {
        $trick = $this->entityManager->getRepository(Trick::class)
            ->findOneById($request->attributes->get('slug'))
        ;

        if ($trick === null) {
            return new ControllerResult(404);
        }

        $form = $this->formFactory->create(
            TrickCommentType::class,
            $comment,
            $this->parameters[$request->attributes->get('_route')]['form_options']
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->remove($trick);
            $this->entityManager->flush();

            $data = array(
                'trick' => $trick,
            );

            return new ControllerResult(204, $data);

        } elseif ($form->isSubmitted()) {
            $data = array(
                'form' => $form->createView(),
            );

            return new ControllerResult(400, $data);
        }
    }
}

