<?php

namespace AppBundle\Tests\Service;

use AppBundle\Entity\Chat;
use AppBundle\Entity\Message;
use AppBundle\Entity\Subscribe;
use AppBundle\Entity\User;
use AppBundle\Service\ModelPushListener;
use AppBundle\Tests\ContainerAwareTest;

class ModelPushListenerTest extends ContainerAwareTest
{
    public function testChat() {
        $chat = new Chat();
        $chat->setName("my chat");

        $listener = $this->buildListener();

        $result = $listener->getMessage($chat);
        $this->assertFalse($result);
    }

    public function testSubscribe() {
        $chat = new Chat();
        $chat->setName("my chat");

        $user1 = new User();
        $user1->setUsername('testuser1');

        $user2 = new User();
        $user2->setUsername('testuser2');

        $subscribe = new Subscribe();
        $subscribe->setChat($chat);
        $subscribe->setUser($user1);

        $sub1 = new Subscribe();
        $sub1->setChat($chat);
        $sub1->setUser($user2);

        $chat->addSubscribe($subscribe);
        $chat->addSubscribe($sub1);

        $serializer = $this->container->get('jms_serializer');
        $usernames[] = 'testuser1';
        $usernames[] = 'testuser2';

        $content = array("subscribe" => $subscribe);
        $message = array("broadcast" => "subscribe", "content" => $serializer->serialize($content, "json"), "usernames" => $usernames);

        $listener = $this->buildListener();

        $result = $listener->getMessage($subscribe);
        $this->assertEquals($message,$result);
    }

    public function testMessage() {
        $chat = new Chat();
        $chat->setName("my chat");

        $user1 = new User();
        $user1->setUsername('testuser1');

        $user2 = new User();
        $user2->setUsername('testuser2');

        $subscribe = new Subscribe();
        $subscribe->setChat($chat);
        $subscribe->setUser($user1);

        $sub1 = new Subscribe();
        $sub1->setChat($chat);
        $sub1->setUser($user2);

        $chat->addSubscribe($subscribe);
        $chat->addSubscribe($sub1);

        $message = new Message();
        $message->setChat($chat);
        $message->setUser($user1);
        $message->setContent("messaging");

        $serializer = $this->container->get('jms_serializer');
        $usernames[] = 'testuser1';
        $usernames[] = 'testuser2';

        $content = array("message" => $message);
        $expected = array("broadcast" => "message", "content" => $serializer->serialize($content, "json"), "usernames" => $usernames);

        $listener = $this->buildListener();

        $result = $listener->getMessage($message);
        $this->assertEquals($expected,$result);
    }

    private function buildListener() {
        $serializer = $this->container->get('jms_serializer');
        $listener = new ModelPushListener($serializer);

        return $listener;
    }
}