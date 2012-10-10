<?php

namespace Symfio\CloudBundle\Cloud;

use Symfio\WebsiteBundle\Entity\Project;

abstract class AbstractCloud implements CloudInterface
{
    public function create($type, $amount = 1)
    {
        $image = $this->getImageByType($type);

        if (!$image instanceof CloudImageInterface) {
            throw new \InvalidArgumentException();
        }

        if ($this->canCreateMultipleInstances()) {
            $instances = $this->createInstances($image, $amount);
        } else {
            $instances = array();
            for ($i = 0; $i < $amount; $i++) {
                $instances[] = $this->createInstance($image);
            }
        }

        return $instances;
    }

    public function terminate(CloudInstanceInterface $instance)
    {
        return $this->terminateInstance($instance);
    }

    public function scale($amount = 1)
    {
        //TODO
    }
}
