<?php

namespace Symfio\CloudBundle\Cloud;

use Symfio\WebsiteBundle\Entity\Project;

interface CloudImageInterface
{
    public function getId();

    public function getOptions();
}
