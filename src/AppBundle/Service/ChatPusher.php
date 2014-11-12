<?php
namespace AppBundle\Service;

use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;
use Ratchet\Wamp\Topic;

class ChatPusher implements WampServerInterface
{
    protected $authorized = array();
    protected $subscribed = array();

    public function onSubscribe(ConnectionInterface $conn, $topic) {
//        echo "subscribed " . $topic->getId() . "\n";

        // Clients subscribe with their session cookie which was authorized by the rest server for username
        $username = null;
        if ($username = $this->authorized[$topic->getId()]) {
            // If the cookie does exist, this connection is kept as a broadcast topic
            $this->subscribed[$topic->getId()] = $topic;
        } else {
            // If the cookie doesn't exist in authorized the connection is closed
            $conn->close();
        }
    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
//        echo "unsubscribe\n";
    }

    public function onOpen(ConnectionInterface $conn)
    {
//        echo "opened\n";
    }

    public function onClose(ConnectionInterface $conn)
    {
//        echo "closed\n";
    }

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        // In this application if clients send data it's because the user hacked around in console
//        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
        $conn->close();
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
    }

    /**
     * @param string $entry
     *
     * Notifies attached usernames if subscribed and broadcasts the new message
     */
    public function onMessage($entry)
    {
        $entryData = json_decode($entry, true);
//        print_r($entryData);
        $content = null;

        $topics = $this->getBroadcastTopics($entryData);
        if (count($topics)) {
            $content = $entryData['content'];
        }

        /** @var Topic $t */
        foreach ($topics as $t) {
            $this->subscribed[$t]->broadcast($content);
//            echo "pushing to topic " . $t;
        }
        
    }

    /**
     * @param $data
     * @return array
     */
    public function getBroadcastTopics($data) {
        if (isset($data["authorize"])) {
//            echo "authorizing " . $data["authorize"] . " for " . $data["username"] . "\n";
            $this->authorized[$data['authorize']] = $data['username'];
        } else if (isset($data["broadcast"])) {
            $usernames = $data['usernames'];
            $content = $data['content'];

            $topicIds = array();

            $usernames = array_values($usernames);

            foreach ($usernames as $u) {
//                echo "doing array keys for " . $u . "\n";
                $topicIds = array_merge(array_keys($this->authorized, $u, true), $topicIds);
            }

//            print_r($topicIds);

            $topics = array_values($topicIds);
            return $topics;
        }
        return array();
    }
}