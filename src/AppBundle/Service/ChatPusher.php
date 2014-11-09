<?php
namespace AppBundle\Service;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class ChatPusher implements WampServerInterface
{
    public function onSubscribe(ConnectionInterface $conn, $topic) {
        echo "subscribed\n";
//        $this->subscribedTopics[$topic->getId()] = $topic;
    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
        echo "unsubscribe\n";
    }

    public function onOpen(ConnectionInterface $conn)
    {
        echo "opened\n";
    }

    public function onClose(ConnectionInterface $conn)
    {
        echo "closed\n";
    }

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
    }

    public function onBlogEntry($entry)
    {
    }

}