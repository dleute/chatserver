<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Ser;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="subscribe")
 * @Ser\ExclusionPolicy("all")
 *
 * @UniqueEntity(
 *     fields={"user", "chat"},
 *     errorPath="user",
 *     message="This user is already subscribed to this chat."
 * )
 */
class Subscribe
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Ser\Expose
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="subscribes")
     * @Ser\Expose
     * @Assert\NotBlank()
     **/
    protected $user;

    /**
     * @ORM\ManyToOne(targetEntity="Chat", inversedBy="subscribes")
     * @Ser\Expose
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
