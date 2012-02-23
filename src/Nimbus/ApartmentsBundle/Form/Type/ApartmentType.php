<?php

namespace Nimbus\ApartmentsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ApartmentType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
          ->add('id', 'hidden')
          ->add('title', 'text')
          ->add('longitude', 'hidden')
          ->add('latitude', 'hidden')
          ->add('street_address', 'text')
          ->add('postal_code', 'text')
          ->add('apartment_number', 'text')
          ->add('description', 'textarea');
    }

    public function getName()
    {
        return 'apartment';
    }
}