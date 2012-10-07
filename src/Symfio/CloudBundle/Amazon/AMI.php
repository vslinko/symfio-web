<?php

namespace Symfio\CloudBundle\Amazon;

use Symfio\CloudBundle\Cloud\CloudImageInterface;

class AMI implements CloudImageInterface
{
    protected $id;
    protected $options;

    public function __construct($id, array $options = array())
    {
        $this->id = $id;
        $this->options = $options;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getOptions()
    {
        return $this->options;
    }
}