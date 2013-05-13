<?php

namespace Metinet\Bundle\FacebookBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Metinet\Bundle\FacebookBundle\Entity\Quizz;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;

/**
 * Quizz controller.
 *
 */
class QuizzController extends Controller {

    /**
     * Lists all Quizz entities.
     *
     * @Route("/", name="quizz")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MetinetFacebookBundle:Quizz')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Quizz entity.
     *
     * @Route("/{id}/show", name="quizz_show")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetFacebookBundle:Quizz')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Quizz entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Quizz entity.
     *
     * @Route("/new", name="quizz_new")
     * @Template()
     */
    public function newAction() {
        $entity = new Quizz();
        $form = $this->createForm(new QuizzType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Quizz entity.
     *
     * @Route("/create", name="quizz_create")
     * @Template("MetinetFacebookBundle:Quizz:new.html.twig")
     */
    public function createAction(Request $request) {
        $entity = new Quizz();
        $form = $this->createForm(new QuizzType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('quizz_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Quizz entity.
     *
     * @Route("/{id}/edit", name="quizz_edit")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetFacebookBundle:Quizz')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Quizz entity.');
        }

        $editForm = $this->createForm(new QuizzType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Quizz entity.
     *
     * @Route("/{id}/update", name="quizz_update")
     * @Template("MetinetFacebookBundle:Quizz:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetFacebookBundle:Quizz')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Quizz entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new QuizzType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('quizz_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Quizz entity.
     *
     * @Route("/{id}/delete", name="quizz_delete")
     * 
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        //Erreur suite a la suppression avec l'ajax .. if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MetinetFacebookBundle:Quizz')->find($id);
            
            
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Quizz entity.');
            }
            $currentQuizz = $this->getQuizz();
            if($entity->getId() != $currentQuizz->getId()){ 
                // on enregistre les modifs en BDD
                $em->remove($entity);
                $em->flush();
            }
       // }

        return $this->redirect($this->generateUrl('administration'));
    }

    /**
     * Deletes a Quizz entity.
     *
     * @Route("/deleteall", name="quizz_deleteall")
     * @Template()
     */
    public function deleteAllAction() {
        $em = $this->getDoctrine()->getManager();
        $allQuizz = $em->getRepository('MetinetFacebookBundle:Quizz')->findAll();
        $currentQuizz = $this->getQuizz();
        foreach ($allQuizz as $oneQuizz){
            if($oneUser->getId() != $currentQuizz->getId()) $em->remove($oneQuizz);
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
     * 
     * Action de modification
     * 
     * @Route("/quizz_modification",name="quizz_modification");
     */
    public function quizzModification() {
        if (isset($_POST['rownum'])){
            $id = $_POST['id'];
            $champ = $_POST['field'];
            $val = $_POST['value'];
            $em = $this->getDoctrine()->getManager();

            $entity = $em->getRepository('MetinetFacebookBundle:Quizz')->find($id);
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
    }

}
