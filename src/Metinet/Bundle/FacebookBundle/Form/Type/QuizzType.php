<?php

namespace Metinet\Bundle\FacebookBundle\Form\Type ;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Metinet\Bundle\FacebookBundle\Entity\Quizz;

class QuizzType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        //$Connexion = $this->getDoctrine()->getEntityManager();
        //$entities = $Connexion->getDoctrine()->getRepository('MetinetFacebookBundle:Quizz')->findAll();
        $builder
                ->add('title', 'text', array(
                    'label'  => 'Titre',
                ))
                ->add('theme', 'entity', array(
                    'class' => 'MetinetFacebookBundle:Theme',
                    'property' => 'title',
                    'label'  => 'Thème',
                ))
                ->add('shortDesc', 'textarea', array(
                    'label'  => 'Résumé',
                ))
                ->add('longDesc', 'textarea', array(
                    'label'  => 'Description',
                ))
                ->add('winPoints', 'text', array(
                    'label'  => 'Points',
                ))
                ->add('averageTime', 'text', array(
                    'label'  => 'Temps estimé',
                ))
                ->add('txtWin1', 'textarea', array(
                    'label'  => 'Texte < 25%',
                ))
                ->add('txtWin2', 'textarea', array(
                    'label'  => 'Texte 25 à 50%',
                ))
                ->add('txtWin3', 'textarea', array(
                    'label'  => 'Texte 50 à 75%',
                ))
                ->add('txtWin4', 'textarea', array(
                    'label'  => 'Texte > 75%',
                ))
                ->add('shareWallTitle', 'textarea', array(
                    'label'  => 'Titre FB',
                ))
                ->add('shareWallDesc', 'textarea', array(
                    'label'  => 'Description FB',
                ))
                ->add('isPromoted', 'checkbox', array(
                    'label'  => 'A la une',
                    'required' => false,
                ))
                ->add('state', 'checkbox', array(
                    'label'  => 'Activé',
                    'required' => false,
                ))
                ->add('file', 'file', array(
                    'label'  => 'image',
                ));
        
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
       $resolver->setDefaults(array(
          'data_class' => 'Metinet\Bundle\FacebookBundle\Entity\Quizz'
       ));
    }
    
    public function getName() {
        return 'register_user';
    }

}

?>
