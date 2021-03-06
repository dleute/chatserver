<?php
// src/Acme/SearchBundle/EventListener/SearchIndexer.php
namespace AppBundle\Service;

use Doctrine\ORM\Event\LifecycleEventArgs;
use AppBundle\Entity\Chat;
use AppBundle\Entity\Message;
use AppBundle\Entity\Subscribe;
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

        if ($message = $this->getMessage($entity)) {
            $context = new ZMQContext();
            $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
            $socket->connect("tcp://localhost:5555");

            $socket->send(json_encode($message));
        }
    }

    public function getMessage($entity) {
        $usernames = array();
        $content = null;
        $chat = null;
        $type = null;


        if ($entity instanceof Message) {
            $content = array("message" => $entity);
            $chat = $entity->getChat();
            $type = "message";
        }

        if ($entity instanceof Subscribe) {
            $content = array("subscribe" => $entity);
            $chat = $entity->getChat();
            $type = "subscribe";
        }

        if ($chat) {

            /** @var Subscribe $s */
            foreach ($chat->getSubscribes() as $s) {
                $usernames[] = $s->getUser()->getUsername();
            }

            $message = array("broadcast" => $type, "content" => $this->serializer->serialize($content, "json"), "usernames" => $usernames);

            return $message;
        } else {
            return false;
        }
    }
}