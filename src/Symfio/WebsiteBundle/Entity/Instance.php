<?php

namespace Symfio\WebsiteBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\SerializerBundle\Annotation as Serializer;
use Symfio\CloudBundle\Cloud\CloudInstanceInterface;

/**
 * @ORM\Entity
 * @ORM\Table("instances", indexes={@ORM\Index(columns={"created_at", "deleted_at"})})
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @Serializer\ExclusionPolicy("ALL")
 */
class Instance implements CloudInstanceInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="ip_address", length=15, nullable=true)
     */
    protected $ipAddress;

    /**
     * @ORM\Column(name="cloud_name")
     */
    protected $cloudName;

    /**
     * @ORM\Column(name="cloud_instance_id")
     */
    protected $cloudInstanceId;

    /**
     * @ORM\Column(name="created_at", type="datetime")
     * @Gedmo\Timestampable(on="create")
     * @Serializer\Expose
     */
    protected $createdAt;

    /**
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     */
    protected $deletedAt;

    /**
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="instances")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="project_owner", referencedColumnName="owner", nullable=false),
     *     @ORM\JoinColumn(name="project_repo", referencedColumnName="repo", nullable=false),
     * })
     */
    protected $project;

    public function __construct(Project $project, $cloudName, $cloudId)
    {
        $this->project = $project;
        $this->cloudName = $cloudName;
        $this->cloudInstanceId = $cloudId;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setIpAddress($address)
    {
        $this->ipAddress = $address;
    }

    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    public function getCloudName()
    {
        return $this->cloudName;
    }

    public function getCloudInstanceId()
    {
        return $this->cloudInstanceId;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getDeletedAt()
    {
        return $this->deletedAt;
    }

    public function getProject()
    {
        return $this->project;
    }
}
