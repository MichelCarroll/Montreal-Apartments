<?php

namespace Nimbus\ApartmentsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nimbus\ApartmentsBundle\Helper\UrlHelper;

/**
 * @ORM\Entity
 * @ORM\Table(name="apartment")
 */
class ApartmentEntry implements Geolocatable
{
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $title;

    public function setTitle($title)
    {
      if ($this->slug == null) 
      {
        $this->slug = UrlHelper::slugify($title);
      }
      $this->title = $title;
    }
    
    /**
     * @ORM\Column(type="string")
     */
    protected $slug;
    
    /**
     * @ORM\Column(type="float")
     */
    protected $longitude;
    
    /**
     * @ORM\Column(type="float")
     */
    protected $latitude;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $street_address;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $postal_code;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $apartment_number;

    /**
     * @return Geocoordinate
     */
    public function getGeocoordinate()
    {
      return new Geocoordinate(
        $this->longitude, 
        $this->latitude
      );
    }
}