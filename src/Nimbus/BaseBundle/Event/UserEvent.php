<?php

namespace Nimbus\BaseBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use FOS\UserBundle\Model\UserInterface;

class UserEvent extends Event
{
  protected $user;
  
  public function __construct(UserInterface $user)
  {
    $this->user = $user;
  }
  
  public function getUser()
  {
    return $this->user;
  }
}
