<?php

namespace Nimbus\BaseBundle\Helper;

use Symfony\Component\HttpFoundation\Response as Response;
use Symfony\Component\Validator\ConstraintViolation as ConstraintViolation;

class RestHelper
{
   
  public static function returnPostResponse($data = array(), $errors = array())
  {
    if(count($errors))
    {
      $legibleErrors = self::getLegibleErrorArray($errors);
      $response = new Response(json_encode(array('success' => false, 'errors' => $legibleErrors)));
      $response->setStatusCode(500);
      return $response;
    }
    
    return new Response(json_encode(array('success' => true, 'data' => $data)));
  }
  
  
  private static function getLegibleErrorArray($errors)
  {
    $legibleErrors = array();
    
    foreach($errors as $key => $error)
    {
      if($error instanceof ConstraintViolation)
      {
        $legibleErrors[$error->getPropertyPath()] = $error->getMessage();
      }
      else if(is_string($key))
      {
        $legibleErrors[$key] = $error;
      }
      else
      {
        $legibleErrors[] = $error;
      }
    }
    
    return $legibleErrors;
  }
}
