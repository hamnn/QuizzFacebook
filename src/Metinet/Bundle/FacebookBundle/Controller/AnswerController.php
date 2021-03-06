<?php

namespace Metinet\Bundle\FacebookBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Metinet\Bundle\FacebookBundle\Entity\Answer;
use Metinet\Bundle\FacebookBundle\Entity\Question;
use Metinet\Bundle\FacebookBundle\Form\Type\AnswerType;
use Metinet\Bundle\FacebookBundle\Form\Type\AnswerFalseType;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;

/**
 * Answer controller.
 *
 */
class AnswerController extends MetinetController {

    /**
     * Lists all Answer entities.
     *
     * @Route("/admin/answer", name="answer")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MetinetFacebookBundle:Answer')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Answer entity.
     *
     * @Route("/admin/{id}/showanswer", name="answer_show")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetFacebookBundle:Answer')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Answer entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Answer entity.
     *
     * @Route("/admin/{id}/newanswer", name="answer_new")
     * @Template()
     */
    public function newAction($id) {
        $entity = new Answer();
        $form = $this->createForm(new AnswerType(), $entity);
        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository('MetinetFacebookBundle:Question')->find($id);
        $answers = $question->getAnswers();
        foreach($answers as $answer){
            
          if ($answer->getIsCorrect() == 1){
              $form = $this->createForm(new AnswerFalseType(), $entity);
          }
        }
        return array(
            'entity' => $entity,
            'id_question' => $id,
            'form' => $form->createView(),
        );
    }
    
    /**
     * Edits state Answer entity.
     *
     * @Route("/admin/{id}/iscorrectanswer", name="answer_iscorrect")
     * @Template("MetinetFacebookBundle:Question:show.html.twig")
     */
    public function isCorrectAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MetinetFacebookBundle:Answer')->find($id);
        $iscorrect = $entity->getIsCorrect();
         $rightanswer = 0;
        if ($iscorrect == 1){
            $entity->setIsCorrect(0);
        }else{
            //On récupère la question
            $question = $em->getRepository('MetinetFacebookBundle:Question')->find($entity->getQuestion()->getId());
            $answers = $question->getAnswers();
            foreach($answers as $answer){
                 if ($answer->getIsCorrect() == 1){
                    $rightanswer += 1;
                    $answer->setIsCorrect(0);
                    $em->persist($answer);
                    $em->flush();
                }
            }
             
            $entity->setIsCorrect(1);
        }
        
        $em->persist($entity);
        $em->flush();
        
        $deleteForm = $this->createDeleteForm($id);
        
        return array(
            'entity' => $entity->getQuestion(),
            'answers' => $entity->getQuestion()->getAnswers(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits title Answer entity.
     *
     * @Route("/admin/answer_ajax", name="answer_ajax")
     * @Template("MetinetFacebookBundle:Question:show.html.twig")
     */
    public function AjaxAction() {
        
        $em = $this->getDoctrine()->getManager();
        if(isset($_POST['id'])){
            //On selectionne juste l'ID dans le string
            $id = substr($_POST['id'], 6);
            $entity = $em->getRepository('MetinetFacebookBundle:Answer')->find($id);
            if(isset($_POST['title'])){
                $title = $_POST['title'];
                $entity->setTitle($title);
                 $em->persist($entity);
                $em->flush();
            }
        }
        
	return new Response($title);
    }

    /**
     * Creates a new Answer entity.
     *
     * @Route("/admin/{id}/createanswer", name="answer_create")
     * @Template("MetinetFacebookBundle:Answer:new.html.twig")
     */
    public function createAction(Request $request, $id) {
        
        
        
        $entity = new Answer();
        $form = $this->createForm(new AnswerType(), $entity);
        
        $em = $this->getDoctrine()->getManager();
        $question = $em->getRepository('MetinetFacebookBundle:Question')->find($id);
        $answers = $question->getAnswers();
        foreach($answers as $answer){
            
          if ($answer->getIsCorrect() == 1){
              $form = $this->createForm(new AnswerFalseType(), $entity);
              $entity->setIsCorrect(0);
          }
        }
        
        $form->bind($request);

        if ($form->isValid()) {
            $question = $em->getRepository('MetinetFacebookBundle:Question')->find($id);
            $entity->setQuestion($question);
            //$entity->upload();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('question_show', array('id' => $entity->getQuestion()->getId())));
        }

        return array(
            'entity' => $entity,
            'id_question' => $id,
            'form' => $form->createView()
        );
    }


    /**
     * Deletes a Answer entity.
     *
     * @Route("/admin/{id}/deleteanswer", name="answer_delete")
     * @Template("MetinetFacebookBundle:Question:show.html.twig")
     */
    
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        //Erreur suite a la suppression avec l'ajax .. if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MetinetFacebookBundle:Answer')->find($id);
            
            
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Answer entity.');
            }
                // on enregistre les modifs en BDD
                $em->remove($entity);
                $em->flush();
            
       // }

        $deleteForm = $this->createDeleteForm($id);
        
        return array(
            'entity' => $entity->getQuestion(),
            'answers' => $entity->getQuestion()->getAnswers(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Answer entity.
     *
     * @Route("/admin/deleteallanswer", name="answer_deleteall")
     * @Template()
     */
    public function deleteAllAction() {
        $em = $this->getDoctrine()->getManager();
        $allAnswer = $em->getRepository('MetinetFacebookBundle:Answer')->findAll();
        $currentAnswer = $this->getAnswer();
        foreach ($allAnswer as $oneAnswer){
            if($oneAnswer->getId() != $currentAnswer->getId()) $em->remove($oneAnswer);
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
