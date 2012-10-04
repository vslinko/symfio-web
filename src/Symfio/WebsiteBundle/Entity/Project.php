<?php

namespace Symfio\WebsiteBundle\Entity;

use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table("projects")
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\Column
     */
    protected $owner;

    /**
     * @ORM\Id
     * @ORM\Column
     */
    protected $name;

    /**
     * When we start bill account
     *
     * @ORM\Column(name="first_instance_created_at", type="datetime", nullable=true)
     */
    protected $firstInstanceCreatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="projects")
     * @ORM\JoinColumn(name="username", referencedColumnName="username", nullable=false)
     */
    protected $user;

    /**
     * @ORM\OneToMany(targetEntity="Instance", mappedBy="project")
     */
    protected $instances;

    public function __construct(User $user, $owner, $name)
    {
        $this->user = $user;
        $this->owner = $owner;
        $this->repository = $name;
        $this->instances = new ArrayCollection();
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function getName()
    {
        return $this->repository;
    }

    public function setFirstInstanceCreatedAt(DateTime $firstInstanceCreatedAt)
    {
        $this->firstInstanceCreatedAt = $firstInstanceCreatedAt;
    }

    public function getFirstInstanceCreatedAt()
    {
        return $this->firstInstanceCreatedAt;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function addInstance(Instance $instance)
    {
        $this->instances[] = $instance;
    }

    public function removeInstance(Instance $instance)
    {
        $this->instances->remove($instance);
    }

    public function getInstances()
    {
        return $this->instances;
    }
}
