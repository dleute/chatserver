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
     * @ORM\ManyToOne(targetEntity="User", inversedBy="subscribes")
     **/
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Chat", inversedBy="subscribes")
     **/
    protected $chat;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     * @return Subscribe
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set chat
     *
     * @param \AppBundle\Entity\Chat $chat
     * @return Subscribe
     */
    public function setChat(\AppBundle\Entity\Chat $chat = null)
    {
        $this->chat = $chat;

        return $this;
    }

    /**
     * Get chat
     *
     * @return \AppBundle\Entity\Chat 
     */
    public function getChat()
    {
        return $this->chat;
    }
}
