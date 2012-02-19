<?php

namespace Nimbus\ApartmentsBundle\Tests\Controller;

use Nimbus\BaseBundle\Test\WebTestCase as WebTestCase;
use Nimbus\ApartmentsBundle\Repository\ApartmentRepository;

class ApartmentControllerTest extends WebTestCase
{

  /**
   * @var \Doctrine\ORM\EntityManager
   */
  private static $em;

  const TITLE_PREFIX = '[[TEST]]';

  public function setUp()
  {
    self::$kernel = static::createKernel();
    self::$kernel->boot();
    self::$em = self::$kernel->getContainer()->get('doctrine.orm.entity_manager');
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
    $client = $this->createClientWithAuthentication('main', array('ROLE_LANDLORD'));

    $title = self::TITLE_PREFIX . 'Some Title Test 1';
    $params = array(
        'title' => $title,
        'street_address' => '5456 Lasalle Boul',
        'postal_code' => 'U2J 3K1',
        'longitude' => 293874982.345,
        'latitude' => 293874982.345,
        'ignoredvalue' => 'ignoredvalue',
    );

    $crawler = $client->request('POST', '/apartment/register', $params);
    $result = json_decode($client->getResponse()->getContent());

    $this->assertTrue($result->success, $client->getResponse()->getContent());

    $apt = self::$em->getRepository('NimbusApartmentsBundle:Apartment')
            ->findOneBy(array('title' => $title));

    $this->assertNotNull($apt);
  }

  public function testRegisterCompleteSameTitle()
  {
    $client = $this->createClientWithAuthentication('main', array('ROLE_LANDLORD'));

    $title = self::TITLE_PREFIX . 'Some Title Test 1';
    $params = array(
        'title' => $title,
        'street_address' => '9027 Different Address Boul',
        'postal_code' => 'U2J 3K1',
        'longitude' => 293875482.345,
        'latitude' => 212374982.345
    );

    $crawler = $client->request('POST', '/apartment/register', $params);
    $result = json_decode($client->getResponse()->getContent());

    $this->assertTrue($result->success, $client->getResponse()->getContent());

    $apt = self::$em->getRepository('NimbusApartmentsBundle:Apartment')
            ->findOneBy(array('title' => $title));

    $this->assertNotNull($apt);
  }

  public function testRegisterIncomplete()
  {
    $client = $this->createClientWithAuthentication('main', array('ROLE_LANDLORD'));

    $title = self::TITLE_PREFIX . 'Some Title Test 2';
    $params = array(
        'title' => $title,
        'street_address' => '5456 Lasalle Boul',
    );

    $crawler = $client->request('POST', '/apartment/register', $params);
    $result = json_decode($client->getResponse()->getContent());

    $this->assertFalse($result->success, $client->getResponse()->getContent());
    $this->assertEquals(500, $client->getResponse()->getStatusCode());
    $this->assertObjectHasAttribute('postal_code', $result->errors);

    $apt = self::$em->getRepository('NimbusApartmentsBundle:Apartment')
            ->findOneBy(array('title' => $title));

    $this->assertEmpty($apt);
  }
  
  public function testRegisterUnauthenticated()
  {
    $client = $this->createClientWithAuthentication('main', array('ROLE_USER'));

    $title = self::TITLE_PREFIX . 'Some Title Test 5';
    $params = array(
        'title' => $title,
        'street_address' => '5456 Lasalle Boul',
        'postal_code' => 'U2J 3K1',
        'longitude' => 293874982.345,
        'latitude' => 293874982.345,
        'ignoredvalue' => 'ignoredvalue',
    );

    $crawler = $client->request('POST', '/apartment/register', $params);
    $result = json_decode($client->getResponse()->getContent());

    $this->assertFalse($result->success, $client->getResponse()->getContent());

    $apt = self::$em->getRepository('NimbusApartmentsBundle:Apartment')
            ->findOneBy(array('title' => $title));

    $this->assertNull($apt);
  }

}