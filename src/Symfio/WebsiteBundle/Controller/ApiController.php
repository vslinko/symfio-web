<?php

namespace Symfio\WebsiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfio\WebsiteBundle\Form\ProjectType;
use Symfio\WebsiteBundle\Entity\Project;

/**
 * @Route("/api")
 */
class ApiController extends Controller
{
    /**
     * @Route("/projects")
     * @Method("POST")
     * @Secure("ROLE_USER")
     */
    public function postProjectAction()
    {
        $form = $this->createForm(new ProjectType(), new Project($this->getUser()));
        $form->bind($this->getRequest()->request->all());

        if ($form->isValid()) {
            $project = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($project);
            $em->flush();

            return $this->response(201, $project);
        }

        return $this->response(400, array(
            'errors' => $form->getErrors(),
        ));
    }

    /**
     * @Route("/projects")
     * @Method("GET")
     * @Secure("ROLE_USER")
     */
    public function getProjects()
    {
        return $this->response(200, $this->getUser()->getProjects());
    }

    private function response($code, $object)
    {
        return new Response($this->get('serializer')->serialize($object, 'json'), $code, array(
            'Content-Type' => 'application/json',
        ));
    }
}
