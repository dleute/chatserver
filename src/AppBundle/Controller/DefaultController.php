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

        $auth = md5(uniqid($username, true));

        $context = new ZMQContext();
        $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://localhost:5555");

        $message = array("authorize" => $auth, "username" => $username);

        $socket->send(json_encode($message));

        $environment = $this->get('kernel')->getEnvironment();

        // This is a cheap hack to not need javascript based routing.
        // Since this is currently a single page app it wasn't worth the effort.
        $base_url = null;
        if ($environment == "prod") {
            $base_url = "/";
        } else {
            $base_url = "/" . "app_" . $environment . ".php/";
        }

        return array('auth' => $auth, 'users' => $entities, 'base_url' => $base_url);
    }
}
