<?php

namespace AppBundle\Tests\Service;

use AppBundle\Entity\Chat;
use AppBundle\Entity\Message;
use AppBundle\Entity\Subscribe;
use AppBundle\Entity\User;
use AppBundle\Service\ChatPusher;
use AppBundle\Tests\ContainerAwareTest;
use Ratchet\Wamp\Topic;

class ChatPusherTest extends ContainerAwareTest
{
    public function testAuthorize() {
        $pusher = new ChatPusher();

        $message = array("authorize" => "nomatter", "username" => "auser");

        $result = $pusher->getBroadcastTopics($message);

        $this->assertEquals(array(), $result);
    }

    public function testSubscribe()
    {
        $pusher = $this->buildPusher();

        $serializer = $this->container->get('jms_serializer');

        $sub = new Subscribe();
        $chat = new Chat();
        $user1 = new User();
        $user1->setUsername("testuser1");

        $chat->setName("chat");
        $sub->setChat($chat);
        $sub->setUser($user1);

        $content = array("subscribe" => $sub);

        $usernames[] = "testuser1";
        $usernames[] = "testuser2";

        $message = array("broadcast" => "subscribe", "content" => $serializer->serialize($content, "json"), "usernames" => $usernames);

        $result = $pusher->getBroadcastTopics($message);

        $topics[] = "user2";
        $topics[] = "user1";

        $this->assertEquals($topics, $result);
    }

    public function testMessage() {
        $pusher = $this->buildPusher();

        $serializer = $this->container->get('jms_serializer');

        $chat = new Chat();
        $chat->setName("chat");

        $user1 = new User();
        $user1->setUsername("testuser1");

        $message = new Message();
        $message->setChat($chat);
        $message->setUser($user1);

        $content = array("message" => $message);

        $usernames[] = "testuser1";
        $usernames[] = "testuser2";

        $message = array("broadcast" => "message", "content" => $serializer->serialize($content, "json"), "usernames" => $usernames);

        $result = $pusher->getBroadcastTopics($message);

        $topics[] = "user2";
        $topics[] = "user1";

        $this->assertEquals($topics, $result);
    }

    private function buildPusher() {
        $pusher = new ChatPusher();

        $auth1 = array("authorize" => "user1", "username" => "testuser1");
        $auth2 = array("authorize" => "user2", "username" => "testuser2");

        // Have to authorize the users
        $pusher->onMessage(json_encode($auth1));
        $pusher->onMessage(json_encode($auth2));

        $topic1 = new Topic('user1');
        $topic2 = new Topic('user2');

        $conn1 = $this->getMock('\\Ratchet\\ConnectionInterface');
        $conn2 = $this->getMock('\\Ratchet\\ConnectionInterface');

        $topic1 = $this->getMock('Topic', array('getId'));
        $topic2 = $this->getMock('Topic', array('getId'));

        $topic1->method('getId')->willReturn('user1');
        $topic2->method('getId')->willReturn('user2');

        // Have to subscribe the users
        $pusher->onSubscribe($conn1,$topic1);
        $pusher->onSubscribe($conn2,$topic2);

        return $pusher;
    }

}