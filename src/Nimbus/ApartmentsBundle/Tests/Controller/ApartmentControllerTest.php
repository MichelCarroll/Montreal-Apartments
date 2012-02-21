<?php

namespace Nimbus\ApartmentsBundle\Tests\Controller;

use Nimbus\BaseBundle\Test\WebTestCase as WebTestCase;
use Nimbus\ApartmentsBundle\Repository\ApartmentRepository;
use Nimbus\ApartmentsBundle\Entity\Apartment;


use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;

class ApartmentControllerTest extends WebTestCase
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
  
  const TITLE_PREFIX = '[[TEST]]';

  const LANDLORD_USERNAME = 'test_landlord';
  const LANDLORD_PASSWORD = 'test_landlord';
  const FIREWALL = 'main';
  
  
  public function setUp()
  {
    self::$kernel = static::createKernel();
    self::$kernel->boot();
    
    self::$em = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');
    self::$sc = self::$kernel->getContainer()->get('security.context');
    self::$um = self::$kernel->getContainer()->get('fos_user.user_manager');
  }

  public function tearDown()
  {
    parent::tearDown();
  }
  
  public static function tearDownAfterClass()
  {
    self::$em->createQuery(
                    "DELETE FROM NimbusApartmentsBundle:Apartment a WHERE a.title LIKE :testTitle")
            ->setParameter('testTitle', '%' . self::TITLE_PREFIX . '%')
            ->execute();

    parent::tearDownAfterClass();
  }

  
  public function testRegisterComplete()
  {
    $client = self::createClient(array(), array(
      'PHP_AUTH_USER' => self::LANDLORD_USERNAME,
      'PHP_AUTH_PW'   => self::LANDLORD_PASSWORD
    ));

    $title = self::TITLE_PREFIX . 'Some Title Test 1';
    $params = array(
        'title' => $title,
        'street_address' => '5456 Lasalle Boul',
        'postal_code' => 'U2J 3K1',
        'longitude' => 293874982.345,
        'latitude' => 293874982.345,
        'ignoredvalue' => 'ignoredvalue',
    );

    $crawler = $client->request('POST', '/apartment/new', $params);
    $result = json_decode($client->getResponse()->getContent());
    
    $this->assertTrue($result->success, $client->getResponse()->getContent());
    $this->assertEquals(200, $client->getResponse()->getStatusCode());
    
    $apt = self::$em->getRepository('NimbusApartmentsBundle:Apartment')
            ->findOneBy(array('title' => $title));

    $this->assertNotNull($apt);
    $this->assertAttributeNotEmpty('id', $result->data);
    
    $this->assertTrue($this->isUserOwnerOfApartment(self::LANDLORD_USERNAME, $apt));
  }

  public function testRegisterCompleteSameTitle()
  {
    $client = self::createClient(array(), array(
      'PHP_AUTH_USER' => 'test_landlord',
      'PHP_AUTH_PW'   => 'test_landlord',
    ));

    $title = self::TITLE_PREFIX . 'Some Title Test 1';
    $params = array(
        'title' => $title,
        'street_address' => '9027 Different Address Boul',
        'postal_code' => 'U2J 3K1',
        'longitude' => 293875482.345,
        'latitude' => 212374982.345
    );

    $crawler = $client->request('POST', '/apartment/new', $params);
    $result = json_decode($client->getResponse()->getContent());

    $this->assertEquals(200, $client->getResponse()->getStatusCode());
    $this->assertTrue($result->success, $client->getResponse()->getContent());

    $apt = self::$em->getRepository('NimbusApartmentsBundle:Apartment')
            ->findOneBy(array('title' => $title));

    $this->assertNotNull($apt);
    $this->assertAttributeNotEmpty('id', $result->data);
    
    $this->assertTrue($this->isUserOwnerOfApartment(self::LANDLORD_USERNAME, $apt));
  }

  public function testRegisterIncomplete()
  {
    $client = self::createClient(array(), array(
      'PHP_AUTH_USER' => 'test_landlord',
      'PHP_AUTH_PW'   => 'test_landlord',
    ));

    $title = self::TITLE_PREFIX . 'Some Title Test 2';
    $params = array(
        'title' => $title,
        'street_address' => '5456 Lasalle Boul',
    );

    $crawler = $client->request('POST', '/apartment/new', $params);
    $result = json_decode($client->getResponse()->getContent());

    $this->assertFalse($result->success, $client->getResponse()->getContent());
    $this->assertEquals(500, $client->getResponse()->getStatusCode());
    $this->assertObjectHasAttribute('postal_code', $result->errors);

    $apt = self::$em->getRepository('NimbusApartmentsBundle:Apartment')
            ->findOneBy(array('title' => $title));
    
    $this->assertObjectNotHasAttribute('data', $result);

    $this->assertEmpty($apt);
  }
  
  public function testRegisterUnauthenticated()
  {
    $client = self::createClient();

    $title = self::TITLE_PREFIX . 'Some Title Test 5';
    $params = array(
        'title' => $title,
        'street_address' => '5456 Lasalle Boul',
        'postal_code' => 'U2J 3K1',
        'longitude' => 293874982.345,
        'latitude' => 293874982.345,
        'ignoredvalue' => 'ignoredvalue',
    );

    $crawler = $client->request('POST', '/apartment/new', $params);
    $result = json_decode($client->getResponse()->getContent());

    $this->assertFalse($result->success, $client->getResponse()->getContent());
    $this->assertEquals(500, $client->getResponse()->getStatusCode());

    $apt = self::$em->getRepository('NimbusApartmentsBundle:Apartment')
            ->findOneBy(array('title' => $title));

    $this->assertObjectNotHasAttribute('data', $result);
    
    $this->assertNull($apt);
  }

  
  
  private function isUserOwnerOfApartment($username, Apartment $apartment)
  {
    $user = self::$um->findUserBy(array('username' => $username));
    $token = new UsernamePasswordToken($user, null, self::FIREWALL, $user->getRoles());
    self::$sc->setToken($token);
    
    return self::$sc->isGranted('OWNER', $apartment);
  }
}