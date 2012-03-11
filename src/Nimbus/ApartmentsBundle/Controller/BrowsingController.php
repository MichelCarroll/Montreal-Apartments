<?php

namespace Nimbus\ApartmentsBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller as Controller;
use Symfony\Component\HttpFoundation\Request as Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Nimbus\ApartmentsBundle\Form\Type\ContactType;
use Nimbus\ApartmentsBundle\Entity\Contact;
use Nimbus\ApartmentsBundle\Entity\Apartment;

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
  
  
  public function detailsAction(Request $request, $slug)
  {
    $this->get('session')->clearFlashes();
    $apartment = $this->attemptApartmentFetch($slug);
    
    $contact = new Contact();
    $contact->setRecipient($apartment->getOwner());
    $form = $this->createForm(new ContactType(), $contact);
    
    if($request->getMethod() == 'POST')
    {
      $form->bindRequest($request);
      
      if($form->isValid())
      {
        $this->sendContactEmail($contact, $apartment);
        $this->get('session')->setFlash('success', 
          'Your message has successfully been sent to the apartment\'s poster.');
      }
      else
      {
        $this->get('session')->setFlash('error', 
          'The contact form hasn\'t been filled out correctly.');
      }
    }
    
    return $this->render('NimbusApartmentsBundle:Browsing:details.html.twig', array(
      'apartment' => $apartment,
      'form' => $form->createView()
    ));
  }
  
  private function sendContactEmail(Contact $contact, Apartment $apartment)
  {
    $em = $this->getDoctrine()->getEntityManager();
    $em->persist($contact);
    $em->flush();
    
    $sender_name = $contact->getName();
    $sender_email = $contact->getEmail();
    $sender_message = $contact->getMessage();
    $recip_email = $contact->getRecipient()->getEmail();
    
    $message = \Swift_Message::newInstance()
        ->setSubject('Message in response to ' . $apartment->getTitle() . ' - Montreal Cribs')
        ->setFrom($sender_email)
        ->setTo($recip_email)
        ->setBody($this->renderView(
          'NimbusApartmentsBundle:Browsing:contact_email.txt.twig', 
          array(
            'name' => $sender_name,
            'message' => $sender_message,
            'apartment' => $apartment,
          )
        ));
    
    $this->get('mailer')->send($message);
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
