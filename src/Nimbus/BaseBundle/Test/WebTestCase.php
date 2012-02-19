<?php

namespace Nimbus\BaseBundle\Test;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as ParentClass;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class WebTestCase extends ParentClass
{

  protected function getCurrentUser()
  {
    return 'user';
  }

  /**
   * User with auth.
   *
   * @param $firewallName
   * @param array $options
   * @param array $server
   *
   * @return Symfony\Bundle\FrameworkBundle\Test\Client|Symfony\Component\BrowserKit\Client
   */
  protected function createClientWithAuthentication($firewallName, array $roles = array(), array $options = array(), array $server = array())
  {
    /* @var $client Client */
    $client = $this->createClient($options, $server);

    // has to be set otherwise "hasPreviousSession" in Request returns false.
    $client->getCookieJar()->set(new Cookie(session_name(), true));

    $user = $this->getCurrentUser();

    $token = new UsernamePasswordToken($user, null, $firewallName, $roles);
    self::$kernel->getContainer()->get('session')->set('_security_' . $firewallName, serialize($token));

    return $client;
  }

}
