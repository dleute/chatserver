<?php
// src/Acme/SearchBundle/EventListener/SearchIndexer.php
namespace AppBundle\Service;

use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Chat;
use AppBundle\Entity\Message;
use AppBundle\Entity\Subscribe;
use AppBundle\Service\ChatPusher;
use \ZMQContext;
use \ZMQ;
use JMS\Serializer\SerializerInterface;

/**
 * Class ModelPushListener
 * @package AppBundle\Service
 *
 * Triggers a notification to the Socket Server that will push the message to appropriate connected clients
 */
class ModelPushListener
{
    protected $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function postPersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        $em = $args->getEntityManager();

        $context = new ZMQContext();
        $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect("tcp://localhost:5555");

        if ($entity instanceof Chat) {
            // Currently there is no reason to do anything with Chat. Adding a subscribe is what adds it to the client.
        }

        if ($entity instanceof Message) {
            // Will be sent to all connected subscribers of that chat
            $chat = $entity->getChat();

            $usernames = array();

            /** @var Subscribe $s */
            foreach ($chat->getSubscribes() as $s) {
                $usernames[] = $s->getUser()->getUsername();
            }

            $content = array("message" => $entity);
            $message = array("broadcast" => "message", "content" => $this->serializer->serialize($content, "json"), "usernames" => $usernames);

            $socket->send(json_encode($message));
        }

        /** @var Subscribe $entity */
        if ($entity instanceof Subscribe) {
            // Sends a chat message. This will happen on new chats for the chat creator and when users are invited

            $chat = $entity->getChat();

            $usernames = array();

            /** @var Subscribe $s */
            foreach ($chat->getSubscribes() as $s) {
                $usernames[] = $s->getUser()->getUsername();
            }

            $content = array("subscribe" => $entity);
            $message = array("broadcast" => "subscribe", "content" => $this->serializer->serialize($content, "json"), "usernames" => $usernames);

            $socket->send(json_encode($message));
        }
    }
}