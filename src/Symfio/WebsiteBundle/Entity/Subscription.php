<?php

namespace Symfio\WebsiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table("subscriptions")
 * @UniqueEntity(fields="email", message="Congrats, you're already subscribed!")
 */
class Subscription
{
    /**
     * @ORM\Id
     * @ORM\Column
     * @Assert\NotBlank(message="We need to know your email")
     * @Assert\Email(message="Ouch, you make a mistake in your address")
     */
    protected $email;

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getEmail()
    {
        return $this->email;
    }
}
