<?php

namespace Nimbus\ApartmentsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ContactType extends AbstractType
{

  public function buildForm(FormBuilder $builder, array $options)
  {
    
    $builder
      ->add('name', 'text', array('required' => true))
      ->add('email', 'email', array('required' => true))
      ->add('message', 'textarea', array('required' => true))
      ->add('recaptcha', 'ewz_recaptcha');
  }

  public function getDefaultOptions(array $options)
  {
      return array(
          'data_class' => 'Nimbus\ApartmentsBundle\Entity\Contact',
      );
  }

  public function getName()
  {
    return 'contact';
  }

}