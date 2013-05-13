<?php

namespace Metinet\Bundle\FacebookBundle\Form\Type ;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class QuizzType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('title', 'text')
                ->add('picture', 'text')
                ->add('shortDesc', 'text')
                ->add('longDesc', 'text');
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
