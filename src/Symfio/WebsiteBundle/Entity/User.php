<?php

namespace Symfio\WebsiteBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\Column
     */
    protected $username;

    /**
     * @ORM\Column(length=40)
     */
    protected $githubToken;

    /**
     * @ORM\Column(length=32)
     */
    protected $token;

    /**
     * @ORM\OneToMany(targetEntity="Project", mappedBy="user")
     */
    protected $projects;

    public function __construct()
    {
        $this->projects = new ArrayCollection();
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setGithubToken($token)
    {
        $this->githubToken = $token;
    }

    public function getGithubToken()
    {
        return $this->githubToken;
    }

    public function setToken($token)
    {
        $this->token = $token;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getPassword()
    {
        return $this->token;
    }

    public function getSalt()
    {
        return null;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }

    public function addProject(Project $project)
    {
        $this->projects[] = $project;
    }

    public function removeProject(Project $project)
    {
        $this->projects->remove($project);
    }

    public function getProjects()
    {
        return $this->projects;
    }

    public function regenerateToken()
    {
        $this->token = md5(mt_rand());
    }
}
