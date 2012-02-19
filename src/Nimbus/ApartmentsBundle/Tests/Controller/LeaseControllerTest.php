<?php

namespace Nimbus\ApartmentsBundle\Tests\Controller;

use Nimbus\BaseBundle\Test\WebTestCase as WebTestCase;
use Nimbus\ApartmentsBundle\Repository\ApartmentRepository;
use Nimbus\ApartmentsBundle\Entity\Apartment;
use Nimbus\ApartmentsBundle\Entity\Lease;


use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use DateTime;

class LeaseControllerTest extends WebTestCase
{

  /**
   * @var \Doctrine\ORM\EntityManager
   */
  private static $em;
  
  /**
   * @var \Symfony\Component\Security\Core\SecurityContext
   */
  private static $sc;
  
  /**
   * @var \FOS\UserBundle\Entity\UserManager
   */
  private static $um;
  
  /**
   * @var Apartment
   */
  private $apt;
  
  const TEST_APARTMENT_TITLE = '[DONT DELETE] Test Title';

  const LANDLORD_USERNAME = 'test_landlord';
  const LANDLORD_PASSWORD = 'test_landlord';
  const FIREWALL = 'main';
  
  
  public static function setUpBeforeClass()
  {
    parent::setUpBeforeClass();
    
    self::$kernel = static::createKernel();
    self::$kernel->boot();
    
    self::$em = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');
    self::$sc = self::$kernel->getContainer()->get('security.context');
    self::$um = self::$kernel->getContainer()->get('fos_user.user_manager');
  }

  public function setUp()
  {
    /* @var $apt Apartment */
    $this->apt = self::$em
            ->getRepository('NimbusApartmentsBundle:Apartment')
            ->findOneBy(array('title' => self::TEST_APARTMENT_TITLE));
  }
  
  public function tearDown()
  {
    $leases = $this->apt->getLeases();
    
    foreach($leases as $lease)
    {
      self::$em->remove($lease);
    }
    self::$em->flush();
  }

  public function testAddLease()
  {
    $lease = new Lease();
    $lease->setStartDate(new DateTime());
    $lease->setMonthlyPrice(12341);
    $lease->setApartment($this->apt);
    
    self::$em->persist($lease);
    self::$em->flush();
  }
}