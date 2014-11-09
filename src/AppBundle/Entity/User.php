<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Subscribe", mappedBy="user")
     **/
    protected $subscribes;

    /**
     * @ORM\OneToMany(targetEntity="Message", mappedBy="user")
     **/
    protected $messages;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

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
     * Add subscribes
     *
     * @param \AppBundle\Entity\Subscribe $subscribes
     * @return User
     */
    public function addSubscribe(\AppBundle\Entity\Subscribe $subscribes)
    {
        $this->subscribes[] = $subscribes;

        return $this;
    }

    /**
     * Remove subscribes
     *
     * @param \AppBundle\Entity\Subscribe $subscribes
     */
    public function removeSubscribe(\AppBundle\Entity\Subscribe $subscribes)
    {
        $this->subscribes->removeElement($subscribes);
    }

    /**
     * Get subscribes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSubscribes()
    {
        return $this->subscribes;
    }

    /**
     * Add messages
     *
     * @param \AppBundle\Entity\Message $messages
     * @return User
     */
    public function addMessage(\AppBundle\Entity\Message $messages)
    {
        $this->messages[] = $messages;

        return $this;
    }

    /**
     * Remove messages
     *
     * @param \AppBundle\Entity\Message $messages
     */
    public function removeMessage(\AppBundle\Entity\Message $messages)
    {
        $this->messages->removeElement($messages);
    }

    /**
     * Get messages
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
