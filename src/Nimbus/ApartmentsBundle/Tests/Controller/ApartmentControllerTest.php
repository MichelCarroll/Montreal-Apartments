<?php

namespace Nimbus\ApartmentsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Nimbus\ApartmentsBundle\Repository\ApartmentRepository;

class ApartmentControllerTest extends WebTestCase
{
  /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $em;

    public function setUp()
    {
        $kernel = static::createKernel();
        $kernel->boot();
        $this->em = $kernel->getContainer()->get('doctrine.orm.entity_manager');
    }
    
    public function testRegisterComplete()
    {
        $client = static::createClient();

        $title = '[[TEST]] Some Title Test 1';
        $params = array(
          'title' => $title,
          'street_address' => '5456 Lasalle Boul',
          'postal_code' => 'U2J 3K1',
        );
        
        $crawler = $client->request('POST', '/apartment/register', $params);
        $result = json_decode($client->getResponse()->getContent());

        $this->assertTrue($result->success);
        
        $apt = $this->em->createQuery('SELECT a FROM NimbusApartmentsBundle:Apartment a WHERE a.title = :title')
          ->setParameter('title', $title)
          ->getResult();
        
        $this->assertNotEmpty($apt);
    }
    
    public function testRegisterIncomplete()
    {
        $client = static::createClient();

        $title = '[[TEST]] Some Title Test 2';
        $params = array(
          'title' => $title,
          'street_address' => '5456 Lasalle Boul',
        );
        
        $crawler = $client->request('POST', '/apartment/register', $params);
        $result = json_decode($client->getResponse()->getContent());

        $this->assertFalse($result->success);
        $this->assertObjectHasAttribute('postal_code', $result->errors);
        
        $apt = $this->em->createQuery('SELECT a FROM NimbusApartmentsBundle:Apartment a WHERE a.title = :title')
          ->setParameter('title', $title)
          ->getResult();
        
        $this->assertEmpty($apt);
    }
}