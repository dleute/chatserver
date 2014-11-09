<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\HttpFoundation\Session\Storage\Handler;
use Symfony\Component\HttpFoundation;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Wamp\WampServer;
use React\EventLoop\Factory;
use React\ZMQ\Context;
use React\Socket\Server;
use AppBundle\Service\ChatPusher;
use \ZMQ;
use \ZMQSocket;

class ChatServerCommand extends ContainerAwareCommand
{
    /**
     * This is boiler plate code for a symfony command line tool
     */
    protected function configure()
    {
        $this
            ->setName('chat:server')
            ->setDescription('Start the Chat server')
            ->addArgument('client-port', InputArgument::REQUIRED, 'What port should it run on?')
            ->addArgument('push-port', InputArgument::REQUIRED, 'What port should pushes come in on?');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \React\Socket\ConnectionException
     *
     * This function starts socket based listeners. The first listens only on localhost for messages to be pushed back
     * to clients. The second listens for connections from clients.
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $loop   = Factory::create();
        $pusher = new ChatPusher();

        $client_port = $input->getArgument('client-port');
        $push_port = $input->getArgument('push-port');

        // Listen for the web server to make a ZeroMQ push after an ajax request
        $context = new Context($loop);

        $pull = $context->getSocket(ZMQ::SOCKET_PULL);
        $pull->bind('tcp://127.0.0.1:'.$push_port); // Binding to 127.0.0.1 means the only client that can connect is itself
        $pull->on('message', array($pusher, 'onBlogEntry'));

        // Set up our WebSocket server for clients wanting real-time updates
        $webSock = new Server($loop);
        $webSock->listen($client_port, '0.0.0.0'); // Binding to 0.0.0.0 means remotes can connect
        $webServer = new IoServer(
            new HttpServer(
                new WsServer(
                    new WampServer(
                        $pusher
                    )
                )
            ),
            $webSock
        );

        $loop->run();
    }
}