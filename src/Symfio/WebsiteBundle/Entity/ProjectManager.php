<?php

namespace Symfio\WebsiteBundle\Entity;

use Doctrine\ORM\EntityManager;

class ProjectManager
{
    protected $em;
    protected $cloudManager;

    public function __construct($cloudManager, EntityManager $em)
    {
        $this->cloudManager = $cloudManager;
        $this->em = $em;
    }

    public function create(Project $project, $type, $amount = 1, $cloud = null)
    {
        $instances = $this->cloudManager->get($cloud)->create($type, $amount);

        foreach ($instances as $instance) {
            $project->addInstance($instance);
            $this->em->persist($instance);
        }

        $this->em->persist($project);
        $this->em->flush();

        return $instances;
    }

    public function terminate(Project $project, $amount = 1)
    {
        $terminated = array();
        $instances = $project->getInstances();

        if ($amount > $instances->count()) {
            $amount = $instances->count();
        }
        
        for ($i = 0; $i < $amount; $i++) {
            $instance = $instances->current();

            if ($instance) {
                if ($this->cloudManager->get($instance->getCloudName())->terminate($instance)) {
                    $terminated[] = $instance;
                    $project->removeInstance($instance);
                }
            }

            $instances->next();
        }

        $this->em->persist($project);
        $this->em->flush();

        return $terminated;
    }

    public function findProjectByOwnerAndRepo($owner, $repo)
    {
        return $this->em->getRepository('Symfio\WebsiteBundle\Entity\Project')
            ->findOneBy(array('owner' => $owner, 'repo'  => $repo));
    }
}
