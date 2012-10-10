<?php

namespace Symfio\CloudBundle\Cloud;

use Symfio\WebsiteBundle\Entity\Instance;

interface CloudInterface
{
    public function getName();

    public function create($type, $amount = 1);

    public function terminate(CloudInstanceInterface $instance);

    public function scale($amount = 1);

    public function getImageByType($type);

    public function createInstance(CloudImageInterface $image);

    public function createInstances(CloudImageInterface $image, $amount);

    public function canCreateMultipleInstances();

    public function terminateInstance(Instance $instance);
}
