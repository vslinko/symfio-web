<?php

namespace Symfio\WebsiteBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RestListener
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        if ($this->isApiRequest($event)) {
            $request = $event->getRequest();
            $parameters = (array) json_decode($request->getContent());
            $request->request->replace($parameters);
        }
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        if ($this->isApiRequest($event)) {
            $exception = $event->getException();

            $statusCode = 500;
            if ($exception instanceof HttpException) {
                $statusCode = $exception->getStatusCode();
            }

            $message = null;
            if (strlen(trim($exception->getMessage())) > 0 && !$exception instanceof NotFoundHttpException) {
                $message = json_encode(array('message' => $exception->getMessage()));
            }

            $response = new Response($message, $statusCode, array(
                'Content-Type' => 'application/json',
            ));

            $event->setResponse($response);
        }
    }

    private function isApiRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if (preg_match('#^/api#', $request->getRequestUri())) {
            if (in_array('text/html', $request->getAcceptableContentTypes())) {
                $event->setResponse(new RedirectResponse('/'));
            } else {
                return true;
            }
        }

        return false;
    }
}
