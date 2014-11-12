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

//use FOS\RestBundle\Serializer\ExceptionWrapperSerializeHandler

/**
 * Chat controller.
 *
 */
class ChatController extends FOSRestController
{

    /**
     * Lists all Chat entities.
     * @Rest\View
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
     * Single chat object.
     * @param $id integer
     * @return array
     *
     * @Rest\View
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
     * @param Request $request
     * @return Response
     * @Rest\View
     */
    public function postChatAction(Request $request)
    {
//        return $this->processForm(new Chat(), $request);
        return $this->handleView($this->processForm(new Chat(),$request));
    }

    /**
     * Creates a new Chat entity.
     *
     * @Rest\View("AppBundle:Chat:new.html.twig")
     */
//    public function postChatAction(Request $request)
//    {
//        $entity = new Chat();
//        $form = $this->createCreateForm($entity);
//        $form->handleRequest($request);
//
//        if ($form->isValid()) {
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($entity);
//            $em->flush();
//
//            return $this->redirect($this->generateUrl('api_chat_show', array('id' => $entity->getId())));
//        }
//
//        return array(
//            'entity' => $entity,
//            'form'   => $form->createView(),
//        );
//    }

    /**
     * Creates a form to create a Chat entity.
     *
     * @param Chat $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Chat $entity)
    {
        $form = $this->createForm(new ChatType(), $entity, array(
            'action' => $this->generateUrl('api_chat_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Chat entity.
     *
     * @Route("/new", name="api_chat_new")
     * @Method("GET")
     * @Rest\View()
     */
//    public function newAction()
//    {
//        $entity = new Chat();
//        $form   = $this->createCreateForm($entity);
//
//        return array(
//            'entity' => $entity,
//            'form'   => $form->createView(),
//        );
//    }

    /**
     * Finds and displays a Chat entity.
     *
     * @Rest\View()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Chat')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Chat entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Chat entity.
     *
     * @Route("/{id}/edit", name="api_chat_edit")
     * @Method("GET")
     * @Rest\View()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Chat')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Chat entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Chat entity.
    *
    * @param Chat $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Chat $entity)
    {
        $form = $this->createForm(new ChatType(), $entity, array(
            'action' => $this->generateUrl('api_chat_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Chat entity.
     *
     * @Route("/{id}", name="api_chat_update")
     * @Method("PUT")
     * @Rest\View("AppBundle:Chat:edit.html.twig")
     */
    public function putChatAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Chat')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Chat entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('api_chat_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Chat entity.
     *
     * @Route("/{id}", name="api_chat_delete")
     * @Method("DELETE")
     */
    public function deleteChatAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Chat')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Chat entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('api_chat'));
    }

    /**
     * Creates a form to delete a Chat entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('api_chat_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
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
