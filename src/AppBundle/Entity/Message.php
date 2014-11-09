<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="message")
 * @ORM\HasLifecycleCallbacks
 */
class Message
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="text")
     */
    protected $content;

    /**
     * @ORM\ManyToOne(targetEntity="Chat", inversedBy="messages")
     */
    protected $message;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    protected $updated_at;

    /**
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps()
    {
        $date = new \DateTime('now');
        $this->setUpdatedAt($date);

        if ($this->getCreatedAt() == null) {
            $this->setCreatedAt($date);
        }

        return $this;
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
     * Set content
     *
     * @param string $content
     * @return Message
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set created_at
     *
     * @param \DateTime $createdAt
     * @return Message
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;

        return $this;
    }

    /**
     * Get created_at
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param \DateTime $updatedAt
     * @return Message
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;

        return $this;
    }

    /**
     * Get updated_at
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Set message
     *
     * @param \AppBundle\Entity\Chat $message
     * @return Message
     */
    public function setMessage(\AppBundle\Entity\Chat $message = null)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return \AppBundle\Entity\Chat 
     */
    public function getMessage()
    {
        return $this->message;
    }
}
