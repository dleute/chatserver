<?php

namespace AppBundle\Controller\Rest;

use AppBundle\Entity\Subscribe;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Message;
use AppBundle\Form\MessageType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

//use FOS\RestBundle\Serializer\ExceptionWrapperSerializeHandler

/**
 * Chat controller.
 *
 */
class MessageController extends FOSRestController
{

    /**
     * Lists all messages in a chat.
     * @param $chat_id integer
     * @return array
     * @Rest\View
     *
     * @ApiDoc(section = "Messages")
     */
    public function getMessagesAction($chat_id)
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Message')->findByChat($chat_id);

        return array(
            'messages' => $entities,
        );
    }

    /**
     * Adds a message to a chat.
     * @param Request $request
     * @return Response
     * @Rest\View
     *
     * @ApiDoc(section = "Messages")
     */
    public function postMessageAction(Request $request)
    {
        return $this->handleView($this->processForm(new Message(), $request));
    }

    /**
     * @param Message $message
     * @param Request $request
     * @return View
     */
    private function processForm(Message $message, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $statusCode = $em->contains($message) ? 201 : 204;

        $form = $this->createForm(new MessageType(), $message);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $message->setUser($this->getUser());

            // Persists the data to the database
            $em->persist($message);
            $em->flush();

            $response = new View();
            $response->setStatusCode($statusCode);

            // set the `Location` header only when creating new resources
            // This is a best practice for restful api. But in this case the client does not use it because
            // data is pushed back through the io socket connection
            if (201 === $statusCode) {
                $response->setLocation(
                    $this->generateUrl(
                        'api_get_message', array('id' => $message->getId()),
                        true // absolute
                    )
                );
            }

            return $response;
        }

        return $this->view($form, 400);
    }
}