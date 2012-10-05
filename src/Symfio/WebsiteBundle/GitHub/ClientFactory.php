<?php

namespace Symfio\WebsiteBundle\GitHub;

use Symfony\Component\Security\Core\SecurityContext;
use Symfio\WebsiteBundle\Entity\User;
use Github\Client;

class ClientFactory
{
    public static function factory(SecurityContext $context)
    {
        $user = $context->getToken()->getUser();

        if (!$user instanceof User) {
            throw new \RuntimeException();
        }

        $client = new Client();
        $client->authenticate($user->getGithubToken(), null, Client::AUTH_HTTP_TOKEN);

        return $client;
    }
}
