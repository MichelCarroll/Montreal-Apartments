<?php

namespace Nimbus\ApartmentsBundle\Handler;

use FOS\UserBundle\Form\Handler\RegistrationFormHandler as ParentClass;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Mailer\MailerInterface;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Nimbus\BaseBundle\Event\UserEvent;

class RegistrationHandler extends ParentClass
{
  
  protected $dispatcher;
  
  public function __construct(Form $form, Request $request, UserManagerInterface $userManager, MailerInterface $mailer, EventDispatcherInterface $dispatcher)
  {
    $this->form = $form;
    $this->request = $request;
    $this->userManager = $userManager;
    $this->mailer = $mailer;
    $this->dispatcher = $dispatcher;
  }
  
  
  protected function onSuccess(UserInterface $user, $confirmation)
  { 
    if($confirmation)
    {
      $user->setEnabled(false);
      $this->mailer->sendConfirmationEmailMessage($user);
    }
    else
    {
      $user->setConfirmationToken(null);
      $user->setEnabled(true);
    }

    $this->dispatcher->dispatch('fos_user.user_registered', new UserEvent($user));   
    
    $this->userManager->updateUser($user);
  }
  
}
