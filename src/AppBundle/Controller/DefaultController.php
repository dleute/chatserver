<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Message;
use AppBundle\Form\MessageType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use \ZMQContext;
use \ZMQ;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Template()
     */
    public function indexAction($name = "Derrek")
    {
        return array('name' => $name);
    }

    /**
     * @Route("/chats", name="chats")
     * @Template()
     *
     * Provides the basic single page html that gets all data restfully.
     */
    public function chatsAction()
    {
        $username = $this->getUser()->getUsername();

        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:User')->findAll();

//        $form = $this->createForm(new MessageType(), new Message());

//        $form = new MessageType(new Message());

        $auth = md5(uniqid($username, true));

        $context = new ZMQContext();
        $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://localhost:5555");

        $message = array("authorize" => $auth, "username" => $username);

        $socket->send(json_encode($message));

        return array('auth' => $auth, 'users' => $entities);
    }
}
