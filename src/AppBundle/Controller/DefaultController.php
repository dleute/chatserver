<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

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
     */
    public function chatsAction()
    {
        return array();
    }
}
