<?php

namespace Nimbus\ApartmentsBundle\Handler;

use Doctrine\ORM\EntityManager;
use Nimbus\ApartmentsBundle\Entity\Apartment as Apartment;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\Security\Acl\Dbal\AclProvider;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use FOS\UserBundle\Model\UserInterface;


class ApartmentHandler
{
  
  /* @var $em EntityManager */
  private $em;
  
  /* @var $security_context SecurityContext */
  private $security_context;
  
  /* @var $acl_provider AclProvider */
  private $acl_provider;
  
  /* @var $request Request */
  private $request;
  
  const ANON_APARTMENT_SESSION = 'anon_apartment';
  
  
  public function __construct(EntityManager $em, SecurityContext $security_context, AclProvider $acl_provider, Request $request)
  {
    $this->em = $em;
    $this->security_context = $security_context;
    $this->acl_provider = $acl_provider;
    $this->request = $request;
  }
  
  /**
   * Saves an apartment
   * 
   * @param Apartment $apartment
   * @return Apartment 
   */
  public function register(Apartment $apartment)
  {
    $apartment = $this->saveApartment($apartment);
    
    if(!$this->setCurrentUserAsOwner($apartment))
    {
      $this->request->getSession()->set(
        self::ANON_APARTMENT_SESSION, $apartment->getId());
    }
    
    return $apartment;
  }
  
  
  public function resetOwnership(UserInterface $user)
  {
    $anon_apartment_id = 
      $this->request->getSession()->get(self::ANON_APARTMENT_SESSION);
    
    if($anon_apartment_id)
    {
      $apartment = $this->em->find(
        'NimbusApartmentsBundle:Apartment', 
        $anon_apartment_id);
      
      $this->applyAclToApartment($user, $apartment);
    }
  }
  
  
  /**
   * Assigns current user to apartment
   * 
   * @param Apartment $apartment
   * @return bool Success 
   */
  private function setCurrentUserAsOwner(Apartment $apartment)
  {
    $user = $this->security_context->getToken()->getUser();
    
    if(!is_object($user))
    {
      return false;
    }
    
    $this->applyAclToApartment($user, $apartment);
    return true;
  }
  
  
  private function applyAclToApartment(UserInterface $user, Apartment $apartment)
  {
    $objectIdentity = ObjectIdentity::fromDomainObject($apartment);
    $acl = $this->acl_provider->createAcl($objectIdentity);

    $securityIdentity = UserSecurityIdentity::fromAccount($user);

    $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
    $this->acl_provider->updateAcl($acl);
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
    $this->em->flush();
    
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
