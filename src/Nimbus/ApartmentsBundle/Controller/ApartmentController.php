<?php

namespace Nimbus\ApartmentsBundle\Controller;

use Nimbus\ApartmentsBundle\Entity\Apartment as Apartment;
use Nimbus\ApartmentsBundle\Form\Type\ApartmentType;

use Nimbus\BaseBundle\Helper\RestHelper;
use Nimbus\BaseBundle\Exception\FriendlyException;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\HttpFoundation\Response as Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;

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
        $handler = $this->get('apartments.apartment_registration_handler');
        $apartment = $handler->register($apartment);

        if($this->get('security.context')->getToken() instanceof AnonymousToken)
        {
          return $this->redirect($this->generateUrl('fos_user_registration_register'));
        }
        
        return $this->render('NimbusApartmentsBundle:Apartment:success.html.twig');
      }
    }
    
    return $this->render('NimbusApartmentsBundle:Apartment:new.html.twig', array(
      'form' => $form->createView() 
    ));
  }
  
  
  
  public function updateAction(Request $request, $slug)
  { 
    $apartment = $this->getDoctrine()
                  ->getRepository('NimbusApartmentsBundle:Apartment')
                  ->findOneBy(array('slug' => $slug));
    
    /* @var $apartment Apartment */
    if(!$apartment)
    {
      throw new NotFoundHttpException('Apartment not found.');
    }
    
    if($this->get('security.context')->isGranted('EDIT', $apartment) === false)
    {
      throw new AccessDeniedHttpException();
    }
    
    $form = $this->createForm(new ApartmentType(), $apartment);
    
    if ($request->getMethod() == 'POST') 
    {
      $form->bindRequest($request);
      
      if($form->isValid())
      {
        $handler = $this->get('apartments.apartment_registration_handler');
        $apartment = $handler->update($apartment);

        if($this->get('security.context')->getToken() instanceof AnonymousToken)
        {
          return $this->redirect($this->generateUrl('fos_user_registration_register'));
        }
        
        return $this->render('NimbusApartmentsBundle:Apartment:success.html.twig');
      }
    }
    
    
    return $this->render('NimbusApartmentsBundle:Apartment:update.html.twig', array(
      'form' => $form->createView(),
      'slug' => $slug,
      'point' => $apartment->getGeocoordinate()
    ));
  }
  
  
  
  public function deleteAction(Request $request)
  { 
    
  }
  
}