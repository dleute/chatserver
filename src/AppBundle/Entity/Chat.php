<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="chat")
 */
class Chat
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
     * @OneToMany(targetEntity="Message", mappedBy="chat")
     **/
    protected $messages;

    /**
     * @OneToMany(targetEntity="Subscribe", mappedBy="chat")
     **/
    protected $subscribes;
}