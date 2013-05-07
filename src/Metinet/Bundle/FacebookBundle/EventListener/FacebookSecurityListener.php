<?php

namespace Metinet\Bundle\FacebookBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * Sets the session on the request.
 *
 * This will also start the session if it was already started during a previous
 * request.
 *
 * @author Jonathan GUILLEMAIN <jonathan@novaway.fr>
 */
class FacebookSecurityListener
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        // REQUEST object
        $request = $event->getRequest();

        if ($request->attributes->has('check-fb')) {
            return;
        }

        if (strpos($request->getPathinfo(), '/admin') === 0 ||
            strpos($request->getPathinfo(), '/_profiler') === 0 ) {

            $request->attributes->set('check-fb', true);
            return;
        }

        if ($request->isXmlHttpRequest()) {
            if (!isset($_COOKIE['fbsr_'.$this->container->getParameter('fb_app_id')])) {
                return -1;
            }
            return;
        }

        // Récupération du SIGNED_REQUEST
        $dataSignedRequest = $this->container->get('fos_facebook.api')->getSignedRequest();


        if (!$dataSignedRequest || isset($_GET['state'])) {
            // Pas dans le canvas Facebook. On redirige sur l'application
            $redirectUrl = $this->container->getParameter('fb_app_canvas_url');
            $response    = new Response(sprintf("<script type='text/javascript'>top.location.href= '%s';</script>", $redirectUrl));
            $event->setResponse($response);
        } else {

            $userId  = $this->container->get('fos_facebook.api')->getUser();
            $session = $this->container->get('session');

            if ( !$userId && !isset($_REQUEST['code']) ) {
                /**
                 * Pas de session ou pas accepté les droits
                 */
                $redirectUrl = $this->container->get('fos_facebook.api')->getLoginUrl(array('scope' => 'email,publish_actions'));
                $response    = new Response(sprintf("<script type='text/javascript'>top.location.href= '%s';</script>", $redirectUrl));
                $event->setResponse($response);
            }
            else {

                if (!$session->has('user') && $userId > 0) {
                    $session->set('user', $this->container->get('metinet.manager.fbuser')->findUserByFbId($userId));
                }

                $request->attributes->set('check-fb', true);
            }
        }
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        $request = $event->getRequest();

        if (isset($_REQUEST['signed_request'])) {
           $cookie = new Cookie('fbsr_'.$this->container->getParameter('fb_app_id'), $_REQUEST['signed_request'], time() + 60 * 15);
           $event->getResponse()->headers->setCookie($cookie);
        }
    }
}