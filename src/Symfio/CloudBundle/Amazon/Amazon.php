<?php

namespace Symfio\CloudBundle\Amazon;

use Symfio\CloudBundle\Cloud\AbstractCloud;
use Symfio\CloudBundle\Cloud\CloudImageInterface;
use Symfio\CloudBundle\Cloud\Exception\CreateInstanceException;
use Symfio\CloudBundle\Cloud\Exception\TerminateInstanceException;
use Symfio\WebsiteBundle\Entity\Instance;

class Amazon extends AbstractCloud
{
    protected $ec2;
    protected $imageFactory;

    public function __construct(array $parameters = array(), $imageFactory)
    {
        $this->ec2 = new \AmazonEC2($parameters);
        $this->imageFactory = $imageFactory;
    }

    public function getName()
    {
        return 'amazon';
    }

    public function getImageByType($type)
    {
        return $this->imageFactory->get($type);
    }

    public function canCreateMultipleInstances()
    {
        return true;
    }

    public function createInstance(CloudImageInterface $image)
    {
        $instances = $this->createInstances($image);

        return $instances[0];
    }

    public function createInstances(CloudImageInterface $image, $amount = 1)
    {
        $response = $this->ec2->run_instances($image->getId(), $amount, $amount, $image->getOptions());

        if (!$response->isOK()) {
            throw new CreateInstanceException();
        }

        $instances = array();
        $instancesSet = is_array($response->body->instancesSet->item) ?
            $response->body->instancesSet->item : array($response->body->instancesSet->item);

        foreach ($instancesSet as $item) {
            $instance = new Instance($this->getName(), (string) $item->instanceId);
            $instances[] = $instance;
        }

        return $instances;
    }

    public function terminateInstance(Instance $instance)
    {
        $response = $this->ec2->terminate_instances($instance->getCloudInstanceId());

        if (!$response->isOK()) {
            throw new TerminateInstanceException();
        }

        return $response->isOK();
    }
}