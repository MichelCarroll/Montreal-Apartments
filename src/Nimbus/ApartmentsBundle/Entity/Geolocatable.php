<?php

namespace Nimbus\ApartmentsBundle\Entity;

interface Geolocatable 
{
  
  /**
   * @return Nimbus\ApartmentsBundle\Entity\Geocoordinate
   */
  public function getGeocoordinate();
  
}
