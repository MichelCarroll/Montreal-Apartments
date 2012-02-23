<?php

namespace Nimbus\ApartmentsBundle\Handler;

use Doctrine\ORM\EntityManager;
use Nimbus\ApartmentsBundle\Entity\Apartment as Apartment;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\Security\Acl\Dbal\AclProvider;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;


class ApartmentHandler
{
  
  /* @var $em EntityManager */
  private $em;
  
  /* @var $security_context SecurityContext */
  private $security_context;
  
  /* @var $acl_provider AclProvider */
  private $acl_provider;
  
  
  
  public function __construct(EntityManager $em, SecurityContext $security_context, AclProvider $acl_provider)
  {
    $this->em = $em;
    $this->security_context = $security_context;
    $this->acl_provider = $acl_provider;
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
    $this->em->flush();
    
    return $apartment;
  }
  
  
  /**
   * Sssigns owner to apartment
   * 
   * @param Apartment $apartment
   * @return bool Success 
   */
  public function setCurrentUserAsOwner(Apartment $apartment)
  {
    if(!is_object($this->security_context->getToken()->getUser()))
    {
      return false;
    }
    
    $this->applyAclToApartment($apartment);
    return true;
  }
  
  
  private function applyAclToApartment(Apartment $apartment)
  {
    $objectIdentity = ObjectIdentity::fromDomainObject($apartment);
    $acl = $this->acl_provider->createAcl($objectIdentity);

    $user = $this->security_context->getToken()->getUser();
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
