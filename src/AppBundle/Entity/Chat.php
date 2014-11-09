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
     * @ORM\OneToMany(targetEntity="Message", mappedBy="chat")
     **/
    protected $messages;

    /**
     * @ORM\OneToMany(targetEntity="Subscribe", mappedBy="chat")
     **/
    protected $subscribes;
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->messages = new \Doctrine\Common\Collections\ArrayCollection();
        $this->subscribes = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     * @return Chat
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add messages
     *
     * @param \AppBundle\Entity\Message $messages
     * @return Chat
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

    /**
     * Add subscribes
     *
     * @param \AppBundle\Entity\Subscribe $subscribes
     * @return Chat
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
}
