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
    
    $apt_slug = $data->get('contact[apartment_slug]', null, true);
    $target_apt = $this->attemptApartmentFetch($apt_slug);
    
    $sender_name = $data->get('contact[name]', null, true);
    $sender_email = $data->get('contact[email]', null, true);
    $sender_message = $data->get('contact[message]', null, true);
    $recip_email = $target_apt->getOwner()->getEmail();
    $apartment_title = $target_apt->getTitle();
    
    $message = \Swift_Message::newInstance()
        ->setSubject('Message in response to ' . $apartment_title . ' - Montreal Cribs')
        ->setFrom($sender_email)
        ->setTo($recip_email)
        ->setBody($this->renderView(
          'NimbusApartmentsBundle:Browsing:contact_email.txt.twig', 
          array(
            'name' => $sender_name,
            'message' => $sender_message,
            'apt_title' => $apartment_title,
          )
        ));
    
    $this->get('mailer')->send($message);
    
    $this->get('session')->setFlash('success', 
      'Your message has successfully been sent to the apartment\'s poster.');
    
    return $this->redirect($this->generateUrl(
      'details_browsing', 
      array('slug' => $apt_slug)
    ));
  }
  
  /**
   *
   * @param string $slug
   * @return Nimbus\ApartmentsBundle\Entity\Apartment
   * @throws NotFoundHttpException 
   */
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
