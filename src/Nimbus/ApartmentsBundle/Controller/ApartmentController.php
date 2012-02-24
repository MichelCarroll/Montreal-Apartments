<?php

namespace Nimbus\ApartmentsBundle\Controller;

use Nimbus\ApartmentsBundle\Entity\Apartment as Apartment;
use Nimbus\ApartmentsBundle\Form\Type\ApartmentType;

use Nimbus\BaseBundle\Helper\RestHelper;
use Nimbus\BaseBundle\Exception\FriendlyException;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\HttpFoundation\Response as Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


use Exception;

class ApartmentController extends Controller
{  
  
  public function newAction(Request $request)
  {
    $apartment = new Apartment();
    $form = $this->createForm(new ApartmentType(), $apartment);
    
    if ($request->getMethod() == 'POST') 
    {
      $form->bindRequest($request);
      
      if($form->isValid())
      {
        $handler = $this->get('apartment_registration_handler');
    
        try
        {
          $apartment = $handler->register($apartment);
          
          $user = $this->get('security.context')->getToken()->getUser();
          if(!is_object($user))
          {
            return $this->redirect($this->generateUrl('fos_user_registration_register'));
          }
        }
        catch(FriendlyException $e)
        {
          $this->get('session')->setFlash('errors', $e->getMessage());
        }
      }
    }
    
    return $this->render('NimbusApartmentsBundle:Apartment:new.html.twig', array(
      'form' => $form->createView() 
    ));
  }
  
  
  public function updateFormAction(Request $request, $slug)
  { 
    
  }
  
  public function updateAction(Request $request)
  { 
    
  }
  
  
  public function deleteAction(Request $request)
  { 
    
  }
  
}