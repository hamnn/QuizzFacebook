<?php

namespace Metinet\Bundle\FacebookBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DefaultController extends Controller {

    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction() {
        
        
        /*$fbConfig = array(
            'appId' => '575560672464968',
            'secret' => '73d59b1c294dc995866d5826be80451c'
        );

        //$facebook = new Facebook($fbConfig);
        //$user = $facebook->getUser();
        $user = $this->container->get('metinet.manager.fbuser')->getUser();
        if ($user) {
            try {
                $attachment = array('message' => 'Le message qui va apparaître sur le Mur',
                    'name' => 'Le nom de ton post si tu veux le retrouver plus tard!',
                    'caption' => 'Le titre de ton post sur le Mur',
                    'picture' => 'http://www.monsite.com/image.gif');

                $results = $this->container->get('metinet.manager.fbuser')->api('/100000481617990/feed/', 'post', $attachment);
            } catch (FacebookApiException $e) {
                echo $e->getMessage();
                $user = null;
            }
        }*/
        return array();
    }

}
