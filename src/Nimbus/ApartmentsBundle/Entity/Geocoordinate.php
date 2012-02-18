<?php

namespace Nimbus\ApartmentsBundle\Entity;

class Geocoordinate 
{
  /* @var $longitude float */
  public $longitude;
  
  /* @var $latitude float */
  public $latitude;
  
  public function __construct($longitude, $latitude)
  {
    $this->longitude = $longitude;
    $this->latitude = $latitude;
  }
}