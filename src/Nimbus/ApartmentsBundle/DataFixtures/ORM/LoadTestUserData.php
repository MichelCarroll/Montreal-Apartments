<?php

namespace Nimbus\ApartmentsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nimbus\ApartmentsBundle\Entity\User;

class LoadUserData implements FixtureInterface
{

  public function load(ObjectManager $manager)
  {
    $user = new User();

    $user->setUsername('test_landlord');
    $user->setPlainPassword('test_landlord');
    $user->setEmail('test@test.com');
    $user->setEnabled(true);

    $user->addRole('ROLE_LANDLORD');

    $manager->persist($user);
    $manager->flush();
  }

}