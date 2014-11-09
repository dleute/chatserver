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
     * @OneToMany(targetEntity="Subscribe", mappedBy="user")
     **/
    protected $subscribes;

    /**
     * @OneToMany(targetEntity="Message", mappedBy="user")
     **/
    protected $messages;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }
}