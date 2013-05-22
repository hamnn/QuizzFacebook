<?php

namespace Metinet\Bundle\FacebookBundle\Form\Type ;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Metinet\Bundle\FacebookBundle\Entity\Answer;

class AnswerType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
                ->add('title', 'text', array(
                    'label'  => false,
                     'attr' => array(
                        'placeholder' => 'Nouvelle réponse...',
                    )
                ))
                ->add('isCorrect', 'checkbox', array(
                    'label'  => false,
                    'required' => false,
                    'attr' => array(
                        'class' => 'answercheck'
                    )
                ));
        
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
       $resolver->setDefaults(array(
          'data_class' => 'Metinet\Bundle\FacebookBundle\Entity\Answer'
       ));
    }
    
    public function getName() {
        return 'register_answer';
    }

}

?>
