<?php

namespace Nimbus\ApartmentsBundle\Form\Type;

use Nimbus\ApartmentsBundle\Form\Type\LeaseType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ApartmentType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
          ->add('title', 'text')
                
          ->add('address', 'text', array(
              'attr' => array('class' => 'span4')
          ))
                
          ->add('description', 'textarea', array(
              'attr' => array('class' => 'big_textbox')
          ))
                
          ->add('file')
                
          ->add('is_furnished', 'choice', array(
              'choices' => array( 
                  0 => 'no', 1 => 'yes'),
              'required' => true,
              'expanded' => true))
                
          ->add('size', 'choice', array(
              'choices' => array(
                  '1½' => 'Bachelor',
                  '2½' => '2½',
                  '3½' => '3½',
                  '4½' => '4½',
                  '5½' => '5½',
                  '6½' => '6½'),
              'required' => false,
              'attr' => array('class' => 'input-small')
          ))
                
          //EMBEDDED
          ->add('lease', new LeaseType())
                
          //HIDDEN
          ->add('longitude', 'hidden')
          ->add('latitude', 'hidden')
        
          ->add('recaptcha', 'ewz_recaptcha');
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Nimbus\ApartmentsBundle\Entity\Apartment',
        );
    }
    
    public function getName()
    {
        return 'apartment';
    }
}