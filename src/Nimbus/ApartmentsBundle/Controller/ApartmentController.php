<?php

namespace Nimbus\ApartmentsBundle\Controller;

use Nimbus\ApartmentsBundle\Entity\Apartment as Apartment;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\HttpFoundation\Response as Response;
use Symfony\Component\DependencyInjection\Container as Container;

class ApartmentController extends Controller
{
  
  private static $acceptedApartFields = array(
    'title', 'street_address', 'postal_code', 'apartment_number',
    'longitude', 'latitude'
  );
  
  public function registerAction(Request $request)
  {
    $apartment = new Apartment();
    
    foreach(self::$acceptedApartFields as $field)
    {
      $value = $request->get($field);
      call_user_func(array($apartment, 'set'.Container::camelize($field)), $value);
    }
    
    /* @var $errors Symfony\Component\Validator\ConstraintViolationList */
    $errors = $this->container->get('validator')->validate($apartment);
    
    if(count($errors))
    {
      $legibleErrors = array();
      foreach($errors as $error)
      {
        $legibleErrors[$error->getPropertyPath()] = $error->getMessage();
      }
      return new Response(json_encode(array('success' => false, 'errors' => $legibleErrors)));
    }
    
    return new Response(json_encode(array('success' => true)));
  }
  
}