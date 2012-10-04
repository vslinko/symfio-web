<?php

namespace Symfio\WebsiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @UniqueEntity(fields="username", message="Congrats, you're username already exists!")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="We need to know your username")
     */
    protected $username;

    /**
     * @ORM\Column(type="string")
     */
    protected $token;

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }
}
