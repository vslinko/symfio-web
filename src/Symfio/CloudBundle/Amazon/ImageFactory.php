<?php

namespace Symfio\CloudBundle\Amazon;

class ImageFactory
{
    protected $images;

    public function __construct($images)
    {
        $this->images = $images;
    }

    public function get($name)
    {
        if (!isset($this->images[$name])) {
            throw new \InvalidArgumentException();
        }

        $image = $this->images[$name];

        return new AMI($image['id'], array(
            'InstanceType' => $image['instance_type']
        ));
    }
}