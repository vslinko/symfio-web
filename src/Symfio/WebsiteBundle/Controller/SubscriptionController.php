<?php

namespace Symfio\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfio\WebsiteBundle\Entity\Subscription;

class SubscriptionController extends Controller
{
    /**
     * @Route("/")
     * @Method("GET")
     * @Template
     */
    public function getAction()
    {
        return array(
            'form' => $this->createSubscriptionForm()->createView(),
        );
    }

    /**
     * @Route("/")
     * @Method("POST")
     * @Template("SymfioWebsiteBundle:Subscription:get.html.twig")
     */
    public function postAction()
    {
        $form = $this->createSubscriptionForm();
        $form->bind($this->getRequest());

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($form->getData());
            $em->flush();

            $this->getRequest()->getSession()->getFlashBag()->add('success', "You're successfully subscribed!");

            return $this->redirect($this->generateUrl('symfio_website_subscription_get'));
        }

        return array(
            'form' => $form->createView(),
        );
    }

    private function createSubscriptionForm()
    {
        return $this->createFormBuilder(new Subscription())
            ->add('email', 'email')
            ->getForm();
    }
}
