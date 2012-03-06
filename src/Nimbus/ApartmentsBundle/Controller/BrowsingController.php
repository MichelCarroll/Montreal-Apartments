<?php

namespace Nimbus\ApartmentsBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class BrowsingController extends Controller
{
  
  public function indexAction()
  {
    $apartments = $this->getDoctrine()
            ->getRepository('NimbusApartmentsBundle:Apartment')
            ->getAllActive();
    
    return $this->render('NimbusApartmentsBundle:Browsing:index.html.twig', array(
      'apartments' => $apartments
    ));
  }
  
  
  public function summaryAction($slug)
  {
    $apartment = $this->attemptApartmentFetch($slug);
    return new Response(json_encode($apartment->getSummary()));
  }
  
  
  public function detailsAction($slug)
  {
    $apartment = $this->attemptApartmentFetch($slug);
    return $this->render('NimbusApartmentsBundle:Browsing:details.html.twig', array(
      'apartment' => $apartment
    ));
  }
  
  public function contactApartmentAction(Request $request)
  {
    $data = $request->request;
  }
  
  
  private function attemptApartmentFetch($slug)
  {
    $apartment = $this->getDoctrine()
            ->getRepository('NimbusApartmentsBundle:Apartment')
            ->getBySlug($slug);
    
    if(!$apartment)
    {
      throw new NotFoundHttpException();
    }
    
    return $apartment;
  }
  
}
