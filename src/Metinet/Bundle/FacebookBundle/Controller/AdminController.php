<?php

namespace Metinet\Bundle\FacebookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\SecurityContext;

class AdminController extends Controller
{
    
    
    /**
     * @Route("/admin", name="admin_index")
     * @Template()
     */
    public function indexAction()
    {
	return array();
    }
    
    /**
     * @Route("/admin/login", name="login")
     * @Template()
     */
    public function loginAction()
    {
        $request = $this->getRequest();
        $session = $request->getSession();
        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        return $this->render('MetinetFacebookBundle:Admin:login.html.twig', array(
            // last username entered by the user
            'last_username' => $session->get(SecurityContext::LAST_USERNAME),
            'error'         => $error,
        ));
    }
    
    
    /**
     * @Route("/admin/login_check", name="login_check")
     * @Template()
     */
    public function loginCheckAction()
    {
	// Symfony s'occupe du login_check
    }
    
    
    /**
     * @Route("/admin/logout", name="logout")
     * @Template()
     */
    public function logoutAction()
    {
	// Symfony s'occupe de me délogger
    }
}
