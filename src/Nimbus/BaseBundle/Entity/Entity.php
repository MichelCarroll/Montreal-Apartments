<?php

namespace Nimbus\BaseBundle\Entity;

use Symfony\Component\DependencyInjection\Container as Container;

use IteratorAggregate;
use ArrayIterator;

abstract class Entity implements IteratorAggregate
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
  
  public function getIterator()
  {
    return new ArrayIterator(get_object_vars($this));
  }
  
}