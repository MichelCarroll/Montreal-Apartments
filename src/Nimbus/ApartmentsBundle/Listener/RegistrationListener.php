<?php

namespace Nimbus\ApartmentsBundle\Listener;

use Nimbus\BaseBundle\Event\UserEvent;
use Nimbus\ApartmentsBundle\Handler\ApartmentHandler;

class RegistrationListener
{
  protected $handler;
  
  public function __construct(ApartmentHandler $handler)
  {
    $this->handler = $handler;
  }
  
  public function onUserRegistered(UserEvent $event) 
  {
    $this->handler->resetOwnership($event->getUser());
  }
  
}
