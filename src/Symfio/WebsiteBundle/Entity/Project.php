<?php

namespace Symfio\WebsiteBundle\Entity;

use DateTime;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\SerializerBundle\Annotation as Serializer;
use Symfio\WebsiteBundle\Validator\Constraints\GitHubRepositoryAvailable;

/**
 * @ORM\Entity
 * @ORM\Table("projects")
 * @Serializer\ExclusionPolicy("ALL")
 * @UniqueEntity(fields={"owner", "repo"}, message="Project already exists")
 * @GitHubRepositoryAvailable
 */
class Project
{
    /**
     * @ORM\Id
     * @ORM\Column
     * @Assert\NotBlank(message="You must provide repository owner")
     * @Assert\Regex(pattern="/^[-A-Za-z]+$/", message="Repository owner contains invalid symbols")
     * @Serializer\Expose
     */
    protected $owner;

    /**
     * @ORM\Id
     * @ORM\Column
     * @Assert\NotBlank(message="You must provide repository name")
     * @Assert\Regex(pattern="/^[-A-Za-z]+$/", message="Repository name contains invalid symbols")
     * @Serializer\Expose
     */
    protected $repo;

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
     * @Serializer\Expose
     */
    protected $instances;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->instances = new ArrayCollection();
    }

    public function setOwner($owner)
    {
        $this->owner = $owner;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function setRepo($repo)
    {
        $this->repo = $repo;
    }

    public function getRepo()
    {
        return $this->repo;
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
