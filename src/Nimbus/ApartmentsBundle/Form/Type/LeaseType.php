<?php

namespace Nimbus\ApartmentsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class LeaseType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
          ->add('start_date', 'date')
          ->add('monthly_price', 'money');
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'Nimbus\ApartmentsBundle\Entity\Lease',
        );
    }
    
    public function getName()
    {
        return 'lease';
    }
}