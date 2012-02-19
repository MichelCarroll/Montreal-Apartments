<?php

namespace Nimbus\ApartmentsBundle\Handler\Apartment;

use Doctrine\ORM\EntityManager;
use Nimbus\ApartmentsBundle\Entity\Apartment as Apartment;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\Security\Acl\Dbal\AclProvider;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;


class RegistrationHandler
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
    $apartment = $this->setCurrentUserAsOwner($apartment);
    
    
    return $apartment;
  }
  
  private function setCurrentUserAsOwner(Apartment $apartment)
  {
    $objectIdentity = ObjectIdentity::fromDomainObject($apartment);
    $acl = $this->acl_provider->createAcl($objectIdentity);

    $user = $this->security_context->getToken()->getUser();
    $securityIdentity = UserSecurityIdentity::fromAccount($user);

    $acl->insertObjectAce($securityIdentity, MaskBuilder::MASK_OWNER);
    $this->acl_provider->updateAcl($acl);
    
    return $apartment;
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
