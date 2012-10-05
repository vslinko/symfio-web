<?php

namespace Symfio\WebsiteBundle\User;

use Doctrine\ORM\EntityManager;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfio\WebsiteBundle\Entity\User;

class Provider implements OAuthAwareUserProviderInterface
{
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $user = $this->em->getRepository('SymfioWebsiteBundle:User')->find($response->getNickname());

        if (!$user) {
            $user = new User();
            $user->setUsername($response->getNickname());
            $user->setGithubToken($response->getAccessToken());
            $user->regenerateToken();

            $this->em->persist($user);
            $this->em->flush();
        }

        if ($user->getGithubToken() != $response->getAccessToken()) {
            $user->setGithubToken($response->getAccessToken());

            $this->em->flush();
        }

        return $user;
    }
}
