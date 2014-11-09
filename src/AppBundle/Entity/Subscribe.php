<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="subscribe")
 */
class Subscribe
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected $name;

    /**
     * @ManyToOne(targetEntity="User", mappedBy="subscribes")
     **/
    protected $user;

    /**
     * @ManyToOne(targetEntity="Chat", inversedBy="subscribes")
     **/
    protected $chat;
}