<?php

namespace Nimbus\ApartmentsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Nimbus\ApartmentsBundle\Entity\Apartment;

class LoadTestApartmentData implements FixtureInterface
{

  public function load(ObjectManager $manager)
  {
    $apt = new Apartment();
    
    $apt->setApartmentNumber('3');
    $apt->setDescription('Test Description');
    $apt->setTitle('[DONT DELETE] Test Title');
    $apt->setLatitude(198273.51);
    $apt->setLongitude(349262.123);
    $apt->setStreetAddress('123 Boul Lasalle');
    $apt->setPostalCode('J1F K4J');

    $manager->persist($apt);
    $manager->flush();
  }

}