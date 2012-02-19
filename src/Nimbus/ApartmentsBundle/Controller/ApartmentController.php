<?php

namespace Nimbus\ApartmentsBundle\Controller;

use Nimbus\ApartmentsBundle\Entity\Apartment as Apartment;
use Nimbus\BaseBundle\Helper\RestHelper;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Symfony\Component\HttpFoundation\Request as Request;

use Exception;

class ApartmentController extends Controller
{
  
  public function registerAction(Request $request)
  {
    $apartment = new Apartment();
    $apartment->fromArray($request->request->all());
    
    /* @var $errors Symfony\Component\Validator\ConstraintViolationList */
    $errors = $this->container->get('validator')->validate($apartment);
    
    if(!count($errors))
    {
      try
      {
        $this->get('apartment_registration_handler')->register($apartment);
      }
      catch(Exception $e)
      {
        $errors = array('message' => $e->getMessage(), 'trace' => $e->getTrace());
      }
    }
    
    return RestHelper::returnPostResponse($errors);
  }
  
}