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
use AppBundle\Entity\Chat;
use AppBundle\Form\ChatType;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

//use FOS\RestBundle\Serializer\ExceptionWrapperSerializeHandler

/**
 * Chat controller.
 *
 */
class ChatController extends FOSRestController
{

    /**
     * Lists all Chats.
     * @Rest\View
     *
     * @ApiDoc(section = "Chats")
     */
    public function getChatsAction()
    {
        $em = $this->getDoctrine()->getManager();

        $qb = $em->getRepository('AppBundle:Chat')->createQueryBuilder('c')
            ->leftJoin('c.subscribes', 's')
            ->where('s.user = :user')
            ->setParameter('user', $this->getUser());

        $q = $qb->getQuery();

        $entities = $q->getResult();

        return array(
            'chats' => $entities,
        );
    }

    /**
     * Gets single chat.
     * @param $id integer
     * @return array
     * @Rest\View
     *
     * @ApiDoc(section = "Chats")
     */
    public function getChatAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Chat')->find($id);

        return array(
            'chat' => $entity,
        );
    }

    /**
     * Creates a new chat
     * @param Request $request
     * @return Response
     * @Rest\View
     *
     * @ApiDoc(section = "Chats")
     */
    public function postChatAction(Request $request)
    {
        return $this->handleView($this->processForm(new Chat(),$request));
    }

    /**
     * @param Chat $chat
     * @param Request $request
     * @return View
     */
    private function processForm(Chat $chat, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $statusCode = $em->contains($chat) ? 201 : 204;

        $form = $this->createForm(new ChatType(), $chat);
        $form->handleRequest($request);

        if ($form->isValid()) {

            // Persists the data to the database
            $em->persist($chat);
            $subscribe = new Subscribe();
            $subscribe->setUser($this->getUser());
            $subscribe->setChat($chat);
            $chat->addSubscribe($subscribe);
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
                        'get_chat', array('id' => $chat->getId()),
                        true // absolute
                    )
                );
            }

            return $response;
        }

        return $this->view($form, 400);
    }
}
