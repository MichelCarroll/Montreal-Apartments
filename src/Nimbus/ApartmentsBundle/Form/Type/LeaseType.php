<?php

namespace Nimbus\ApartmentsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class LeaseType extends AbstractType
{

  public function buildForm(FormBuilder $builder, array $options)
  {
    
    $builder
            ->add('start_date', 'choice', array(
                'choices' => $this->getPossibleDateList(),
                'required' => false,
                'attr' => array('class' => 'span2')))
            
            ->add('monthly_price', 'money');
  }
  
  private function getPossibleDateList()
  {
    $month = date("m",strtotime(time()));
    
    $months = array();
    
    $time_value = date('Y-m-d', mktime(0, 0, 0, $month, 1));
    $months[$time_value] = 'Immediately';
    
    for($i = 1; $i <= 6; $i++)
    {
      $month_int = ($i + $month) % 12;
      $time_value = date('Y-m-d', mktime(0, 0, 0, $month_int, 1));
      $months[$time_value] = date("M", mktime(0, 0, 0, $month_int, 1));
      $months[$time_value] .= ' 1st';
    }
    
    return $months;
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