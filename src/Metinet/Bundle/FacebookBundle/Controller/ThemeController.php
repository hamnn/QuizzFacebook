<?php

namespace Metinet\Bundle\FacebookBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Metinet\Bundle\FacebookBundle\Entity\Theme;
use Metinet\Bundle\FacebookBundle\Form\Type\ThemeType;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;

/**
 * Theme controller.
 *
 */
class ThemeController extends MetinetController {

    /**
     * Lists all Theme entities.
     *
     * @Route("/admin/theme", name="theme")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MetinetFacebookBundle:Theme')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Theme entity.
     *
     * @Route("/admin/{id}/showtheme", name="theme_show")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetFacebookBundle:Theme')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Theme entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Theme entity.
     *
     * @Route("/admin/newtheme", name="theme_new")
     * @Template()
     */
    public function newAction() {
        $entity = new Theme();
        $form = $this->createForm(new ThemeType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Theme entity.
     *
     * @Route("/admin/createtheme", name="theme_create")
     * @Template("MetinetFacebookBundle:Theme:new.html.twig")
     */
    public function createAction(Request $request) {
        $entity = new Theme();
        $form = $this->createForm(new ThemeType(), $entity);
        $form->bind($request);
        $file = $form->get('file')->getData();
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if(!isset($file))
                $entity->SetPicture("");
            //$entity->upload();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('theme_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Theme entity.
     *
     * @Route("/admin/{id}/edittheme", name="theme_edit")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetFacebookBundle:Theme')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Theme entity.');
        }

        $editForm = $this->createForm(new ThemeType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Theme entity.
     *
     * @Route("/admin/{id}/updatetheme", name="theme_update")
     * @Template("MetinetFacebookBundle:Theme:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetFacebookBundle:Theme')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Theme entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new ThemeType(), $entity);
        $editForm->bind($request);
        
        $file = $editForm->get('file')->getData();
        
        if(isset($file)){
           $picture = sha1(uniqid(mt_rand(), true)).$file->getClientOriginalName();
            
            $entity->setPicture($picture);
        }

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('theme_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Theme entity.
     *
     * @Route("/admin/{id}/deletetheme", name="theme_delete")
     * 
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        //Erreur suite a la suppression avec l'ajax .. if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MetinetFacebookBundle:Theme')->find($id);
            
            
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Theme entity.');
            }
                // on enregistre les modifs en BDD
                $em->remove($entity);
                $em->flush();
            
       // }

        return $this->redirect($this->generateUrl('theme'));
    }

    /**
     * Deletes a Theme entity.
     *
     * @Route("/admin/deletealltheme", name="theme_deleteall")
     * @Template()
     */
    public function deleteAllAction() {
        $em = $this->getDoctrine()->getManager();
        $allTheme = $em->getRepository('MetinetFacebookBundle:Theme')->findAll();
        $currentTheme = $this->getTheme();
        foreach ($allTheme as $oneTheme){
            if($oneTheme->getId() != $currentTheme->getId()) $em->remove($oneTheme);
        }
            
        $em->flush();

        return $this->redirect($this->generateUrl('homepage'));
    }
    
    

    private function createDeleteForm($id) {
        return $this->createFormBuilder(array('id' => $id))
                        ->add('id', 'hidden')
                        ->getForm()
        ;
    }
    
    
    /**
     * Liste des thèmes de quizz du front office
     *
     * @Route("/theme", name="theme_quizz")
     * @Template()
     */
    public function accueilThemeAction() {
	// instanciation des repositories
        $themeRepository = $this->getDoctrine()->getRepository('MetinetFacebookBundle:Theme');
        $entities = $this->getDoctrine()->getRepository('MetinetFacebookBundle:Quizz');
        $questions = $this->getDoctrine()->getRepository('MetinetFacebookBundle:Question');
        
	$themes = $themeRepository->findAll();
        $quizz = $entities->findAll();
        
        foreach($themes as $t){
            
            $nb = $entities->getNbQuizzByTheme($t);
            $t->SetNbQuizz($nb);
        }
        
        foreach($quizz as $q){
            
            $nb = $questions->getNombreQuestionsPourQuizz($q);
            $q->SetNbQuestion($nb);
        }
        
        $fbAppId = $this->container->getParameter('fb_app_id');
        
        return array(
            "themes" => $themes,
            "quizz" => $quizz,
            'fbAppId' => $fbAppId
            );
    }

   
    
    /**
     * 
     * Action de modification
     * 
     * @Route("/theme_modification",name="theme_modification");
     */
    /*public function themeModification() {
        if (isset($_POST['rownum'])){
            $id = $_POST['id'];
            $champ = $_POST['field'];
            $val = $_POST['value'];
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('MetinetFacebookBundle:Theme')->find($id);
            if (!empty($champ) && isset($champ) && !empty($val)){
                if ($champ == 'firstname'){
                    $entity->setFirstname($val);
                }
                if ($champ == 'lastname'){
                    $entity->setLastname($val);
                }
                if ($champ == 'year'){
                    $entity->setPromoYear($val);
                }
                if ($champ == 'email'){
                    $entity->setEmail($val);
                }
                
                $em->persist($entity);
                $em->flush();
                
               return new Response();
            }
            
        }
    }*/

}
