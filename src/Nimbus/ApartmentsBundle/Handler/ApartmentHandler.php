<?php

namespace Nimbus\ApartmentsBundle\Handler;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\LifecycleEventArgs;

use Nimbus\ApartmentsBundle\Entity\Apartment as Apartment;
use Nimbus\BaseBundle\Event\UserEvent;
use Nimbus\ApartmentsBundle\Entity\User;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\Security\Acl\Dbal\AclProvider;
use Symfony\Component\Security\Acl\Domain\ObjectIdentity;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Permission\MaskBuilder;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

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
  
  
  /**
   * Updates an existing apartment
   * 
   * @param Apartment $apartment
   * @return Apartment 
   */
  public function update(Apartment $apartment)
  {
    $apartment = $this->saveApartment($apartment);
    
    return $apartment;
  }
  
  
  
  public function onUserRegistered(UserEvent $event) 
  {
    $this->resetOwnership($event->getUser());
  }
  
  public function onUserLogin(InteractiveLoginEvent $event) 
  {
    /* @var $token TokenInterface */
    $token = $event->getAuthenticationToken();
    $this->resetOwnership($token->getUser());
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
      
      /* @var $apartment Apartment */
      $apartment->setOwner($user);
      
      $this->em->persist($apartment);
      $this->em->persist($user);
      $this->em->flush();
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
    if(!$apartment->getId())
    {
      $this->refreshSlugUniqueness($apartment);
    }
    
    $this->em->persist($apartment->getLease());
    $this->em->persist($apartment);
    $this->em->flush();
    
    return $apartment;
  }
  
  
  private function refreshSlugUniqueness(Apartment &$apartment)
  {
      $apartment->setSlug(
              $this->getUniqueSlug($apartment->getSlug()));
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
    
    /* @var Apartment $apt  */
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
