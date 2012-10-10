<?php

namespace Symfio\CloudBundle\Cloud;

class CloudManager
{
    protected $defaultCloud;

    public function __construct($defaultCloud)
    {
        $this->defaultCloud = $defaultCloud;
    }

    protected $clouds = array();

    public function set($clouds)
    {
        $this->clouds = $clouds;
    }

    public function get($name = null)
    {
        if ('default' === $name || !$name) {
            $name = $this->defaultCloud;
        }

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
