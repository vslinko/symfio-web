<?php

namespace Symfio\CloudBundle\Cloud;

use Symfio\WebsiteBundle\Entity\Project;

abstract class AbstractCloud implements CloudInterface
{
    public function create(Project $project, $type, $amount = 1)
    {
        $image = $this->getImageByType($type);

        if (!$image instanceof CloudImageInterface) {
            throw new \InvalidArgumentException();
        }

        if ($this->canCreateMultipleInstances()) {
            $instances = $this->createInstances($project, $image, $amount);
        } else {
            $instances = array();
            for ($i = 0; $i < $amount; $i++) {
                $instances[] = $this->createInstance($project, $image);
            }
        }

        return $instances;
    }

    public function terminate(Project $project, $amount = 1)
    {
        $removed = array();
        $instances = $project->getInstances();

        if ($amount > $instances->count()) {
            $amount = $instances->count();
        }
        
        for ($i = 0; $i < $amount; $i++) {
            $instance = $instances->current();

            if ($instance) {
                if ($this->terminateInstance($instance)) {
                    $removed[] = $instance;
                }
            }

            $instances->next();
        }

        return $removed;
    }

    public function scale(Project $project, $amount = 1)
    {
        //TODO
    }
}
