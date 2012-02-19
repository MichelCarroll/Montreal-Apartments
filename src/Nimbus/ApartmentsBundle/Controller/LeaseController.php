<?php

namespace Nimbus\ApartmentsBundle\Controller;

use Nimbus\ApartmentsBundle\Entity\Lease as Lease;
use Nimbus\BaseBundle\Helper\RestHelper;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Symfony\Component\HttpFoundation\Request as Request;

use Exception;

class LeaseController extends Controller
{
  
  public function offerAction(Request $request)
  {
    $lease = new Lease();
    $lease->fromArray($request->request->all());
    
    var_dump($lease); die();
    
    /* @var $errors Symfony\Component\Validator\ConstraintViolationList */
//    $errors = $this->container->get('validator')->validate($lease);
//    
//    if(!count($errors))
//    {
//      try
//      {
//        $this->get('apartment_registration_handler')->register($apartment);
//      }
//      catch(Exception $e)
//      {
//        $errors = array('message' => $e->getMessage(), 'trace' => $e->getTrace());
//      }
//    }
//    
//    return RestHelper::returnPostResponse($errors);
  }
  
  
}