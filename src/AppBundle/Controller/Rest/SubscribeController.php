<?php

namespace AppBundle\Controller\Rest;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Subscribe;
use AppBundle\Form\SubscribeType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

/**
 * Subscribe controller.
 *
 */
class SubscribeController extends FOSRestController
{

    /**
     * Lists all subscribers to a chat.
     * @param $id integer
     * @return array
     * @Rest\View
     *
     * @ApiDoc(section = "Subscribers")
     */
    public function getSubscribesAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Subscribe')->findByChat($id);

        return array(
            'subscribes' => $entities,
        );
    }

    /**
     * Subscribe a user to a chat
     * @param Request $request
     * @return Response
     * @Rest\View
     *
     * @ApiDoc(section = "Subscribers")
     */
    public function postSubscribeAction(Request $request)
    {
        return $this->handleView($this->processForm(new Subscribe(), $request));
    }

    /**
     * @param Subscribe $subscribe
     * @param Request $request
     * @return View
     */
    private function processForm(Subscribe $subscribe, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $statusCode = $em->contains($subscribe) ? 201 : 204;

        $form = $this->createForm(new SubscribeType(), $subscribe);
        $form->handleRequest($request);

        if ($form->isValid()) {

            // Persists the data to the database
            $em->persist($subscribe);
            $em->flush();

            $response = new View();
            $response->setStatusCode($statusCode);

            // set the `Location` header only when creating new resources
            // This is a best practice for restful api. But in this case the client does not use it because
            // data is pushed back through the io socket connection
            if (201 === $statusCode) {
                $response->setLocation(
                    $this->generateUrl(
                        'api_get_subscribe', array('id' => $subscribe->getId()),
                        true // absolute
                    )
                );
            }

            return $response;
        }

        return $this->view($form, 400);
    }
}