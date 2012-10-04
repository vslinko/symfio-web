<?php

namespace Symfio\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfio\WebsiteBundle\Entity\User;

class ConnectController extends Controller
{
    /**
     * @Route("/connect/{service}")
     * @Method("GET")
     * @Template("SymfioWebsiteBundle:Connect:get.html.twig")
     */
    public function getAction(Request $request, $service)
    {
        $resourceOwner = $this->getResourceOwnerByName($service);
        $form = $this->createUserForm();

        if (!$resourceOwner->handles($request)) {
            return array();
        }

        $accessToken = $resourceOwner->getAccessToken(
            $request,
            $this->generateUrl('symfio_website_connect_get', array('service' => $service), true)
        );
        $userInformation = $resourceOwner->getUserInformation($accessToken);    
        
        $form->bind(array(
            'username'  => $userInformation->getUsername(),
            'token'     => $userInformation->getAccessToken(),
        ));

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($form->getData());
            $em->flush();
        }

        return array(
            'user' => $form->getData(),
            'form' => $form->getErrorsAsString(),
        );
    }

    /**
     * @Route("/connect/{service}/redirect")
     * @Method("GET")
     */
    public function redirectAction($service)
    {
        $resourceOwner = $this->getResourceOwnerByName($service);

        return new RedirectResponse(
            $resourceOwner->getAuthorizationUrl(
                $this->generateUrl('symfio_website_connect_get', array('service' => $service), true)
            )
        );
    }

    protected function getResourceOwnerByName($name)
    {
        $ownerMap = $this->container->get('hwi_oauth.resource_ownermap.'.$this->container->getParameter('hwi_oauth.firewall_name'));

        if (null === $resourceOwner = $ownerMap->getResourceOwnerByName($name)) {
            throw new \RuntimeException(sprintf("No resource owner with name '%s'.", $name));
        }

        return $resourceOwner;
    }

    private function createUserForm()
    {
        return $this->createFormBuilder(new User())
            ->add('username')
            ->add('token')
            ->getForm();
    }
}
