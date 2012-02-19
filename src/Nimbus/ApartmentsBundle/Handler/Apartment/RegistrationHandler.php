<?php

namespace Nimbus\ApartmentsBundle\Handler\Apartment;

use Doctrine\ORM\EntityManager;
use Nimbus\ApartmentsBundle\Entity\Apartment as Apartment;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class RegistrationHandler
{
  
  /* @var $em EntityManager */
  private $em;
  
  /* @var $security_context SecurityContext */
  private $security_context;
  
  public function __construct(EntityManager $em, SecurityContext $security_context, $acl_provider)
  {
    var_dump(get_class($acl_provider)); die();
    $this->em = $em;
    $this->security_context = $security_context;
    $this->acl_provider = $acl_provider;
  }
  
  /**
   * Verifies credentials, and saves an apartment if able.
   * 
   * @param Apartment $apartment
   * @return Apartment 
   */
  public function register(Apartment $apartment)
  {
    if(!$this->security_context->isGranted('ROLE_LANDLORD'))
    {
      throw new AccessDeniedException();
    }
    
    $apartment = $this->saveApartment($apartment);
    
    
    $this->em->flush();
  }
  
  
  
  /**
   *
   * @param Apartment $apartment
   * @return Apartment 
   */
  private function saveApartment(Apartment $apartment)
  {
    $apartment->setSlug(
            $this->getUniqueSlug($apartment->getSlug()));
    
    $this->em->persist($apartment);
    
    return $apartment;
  }
  
  
  /**
   *
   * @param string $slug
   * @return string 
   */
  private function getUniqueSlug($slug)
  {
    $original_slug = $slug;
    $i = 0;
    
    $apt = $this->em->getRepository('NimbusApartmentsBundle:Apartment')
          ->findOneBy(array('slug' => $slug));
    
    while(!is_null($apt))
    {
      $slug = $original_slug . '-' . ++$i;
      $apt = $this->em->getRepository('NimbusApartmentsBundle:Apartment')
            ->findOneBy(array('slug' => $slug));
    }
    return $slug;
  }
  
}
