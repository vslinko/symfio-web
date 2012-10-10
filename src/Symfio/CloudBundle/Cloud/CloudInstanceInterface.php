<?php

namespace Symfio\CloudBundle\Cloud;

interface CloudInstanceInterface
{
    const STATE_PENDING = 0;
    const STATE_RUNNING = 16;
	const STATE_SHUTTING_DOWN = 32;
	const STATE_TERMINATED = 48;
	const STATE_STOPPING = 64;
	const STATE_STOPPED = 80;

    public function getCloudInstanceId();
}