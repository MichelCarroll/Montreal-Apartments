<?php

namespace Nimbus\BaseBundle\Entity;

use Symfony\Component\DependencyInjection\Container as Container;

class Entity
{

  public function fromArray(array $data)
  {
    foreach($data as $key => $val)
    {
      $method_name = 'set'.Container::camelize($key);
      if(method_exists($this, $method_name))
      {
        call_user_func(array($this, $method_name), $val);
      }
      
    }
  }
  
}