<?php

namespace Symfio\CloudBundle\Cloud;

class Manager
{
    protected $clouds = array();

    public function set($clouds)
    {
        $this->clouds = $clouds;
    }

    public function get($name)
    {
        if (!isset($this->clouds[$name])) {
            throw new \InvalidArgumentException();
        }

        return $this->clouds[$name];
    }

    public function add(AbstractCloud $cloud)
    {
        $this->clouds[$cloud->getName()] = $cloud;
    }
}
