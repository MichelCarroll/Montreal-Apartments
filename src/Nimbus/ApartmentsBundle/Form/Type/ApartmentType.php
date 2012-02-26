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
          ->add('address', 'text')
          ->add('description', 'textarea')
                
          //EMBEDDED
          ->add('lease', new LeaseType())
                
          //HIDDEN
          ->add('longitude', 'hidden')
          ->add('latitude', 'hidden');
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