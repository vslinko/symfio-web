<?php

namespace Symfio\CloudBundle\Cloud;

use Symfio\WebsiteBundle\Entity\Project;
use Symfio\WebsiteBundle\Entity\Instance;

interface CloudInterface
{
    public function getName();

    public function create(Project $project, $type, $amount = 1);

    public function terminate(Project $project, $amount = 1);

    public function scale(Project $project, $amount = 1);

    public function getImageByType($type);

    public function createInstance(Project $project, CloudImageInterface $image);

    public function createInstances(Project $project, CloudImageInterface $image, $amount);

    public function canCreateMultipleInstances();

    public function terminateInstance(Instance $instance);
}
