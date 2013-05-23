<?php

namespace Metinet\Bundle\FacebookBundle\Form\Type ;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class ThemeType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('title', 'text', array(
                    'label'  => 'Titre',
                ))
                ->add('shortDesc', 'textarea', array(
                    'label'  => 'Résumé',
                ))
                ->add('longDesc', 'textarea', array(
                    'label'  => 'Description',
                ))
                ->add('file', 'file', array(
                    'label'  => 'Image',
                    'required' => false
                ));
        
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver) {
       $resolver->setDefaults(array(
          'data_class' => 'Metinet\Bundle\FacebookBundle\Entity\Theme'
       ));
    }
    
    public function getName() {
        return 'register_user';
    }

}

?>
