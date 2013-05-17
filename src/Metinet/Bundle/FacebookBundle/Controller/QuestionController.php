<?php

namespace Metinet\Bundle\FacebookBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Metinet\Bundle\FacebookBundle\Entity\Question;
use Metinet\Bundle\FacebookBundle\Entity\Quizz;
use Metinet\Bundle\FacebookBundle\Form\Type\QuestionType;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;

/**
 * Question controller.
 *
 */
class QuestionController extends Controller {

    /**
     * Lists all Question entities.
     *
     * @Route("/admin/question", name="question")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MetinetFacebookBundle:Question')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Question entity.
     *
     * @Route("/admin/{id}/showquestion", name="question_show")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetFacebookBundle:Question')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Question entity.
     *
     * @Route("/admin/{id}/newquestion", name="question_new")
     * @Template()
     */
    public function newAction($id) {
        $entity = new Question();
        $form = $this->createForm(new QuestionType(), $entity);

        return array(
            'entity' => $entity,
            'id_quizz' => $id,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a new Question entity.
     *
     * @Route("/admin/{id}/createquestion", name="question_create")
     * @Template("MetinetFacebookBundle:Question:new.html.twig")
     */
    public function createAction(Request $request, $id) {
        
        
        
        $entity = new Question();
        $form = $this->createForm(new QuestionType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $quizz = $em->getRepository('MetinetFacebookBundle:Quizz')->find($id);
            $entity->setQuizz($quizz);
            //$entity->upload();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('quizz_show', array('id' => $entity->getQuizz()->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Question entity.
     *
     * @Route("/admin/{id}/editquestion", name="question_edit")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetFacebookBundle:Question')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

        $editForm = $this->createForm(new QuestionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Question entity.
     *
     * @Route("/admin/{id}/updatequestion", name="question_update")
     * @Template("MetinetFacebookBundle:Question:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetFacebookBundle:Question')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new QuestionType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('question_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Question entity.
     *
     * @Route("/admin/{id}/deletequestion", name="question_delete")
     * 
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        //Erreur suite a la suppression avec l'ajax .. if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MetinetFacebookBundle:Question')->find($id);
            
            
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Question entity.');
            }
                // on enregistre les modifs en BDD
                $em->remove($entity);
                $em->flush();
            
       // }

        return $this->redirect($this->generateUrl('question'));
    }

    /**
     * Deletes a Question entity.
     *
     * @Route("/admin/deleteallquestion", name="question_deleteall")
     * @Template()
     */
    public function deleteAllAction() {
        $em = $this->getDoctrine()->getManager();
        $allQuestion = $em->getRepository('MetinetFacebookBundle:Question')->findAll();
        $currentQuestion = $this->getQuestion();
        foreach ($allQuestion as $oneQuestion){
            if($oneQuestion->getId() != $currentQuestion->getId()) $em->remove($oneQuestion);
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


}
