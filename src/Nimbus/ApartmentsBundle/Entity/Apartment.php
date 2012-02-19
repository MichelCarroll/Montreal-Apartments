<?php

namespace Nimbus\ApartmentsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nimbus\ApartmentsBundle\Helper\UrlHelper;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="apartment")
 * @ORM\Entity(repositoryClass="Nimbus\ApartmentsBundle\Repository\ApartmentRepository")
 */
class Apartment implements Geolocatable
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
     * @ORM\Column(type="decimal", scale=3)
     */
    protected $longitude;
    
    /**
     * @ORM\Column(type="decimal", scale=3)
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
     * @ORM\Column(type="text")
     */
    protected $description;

    
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
    
    /**
     * @ORM\OneToMany(targetEntity="Lease", mappedBy="apartment")
     */
    protected $leases;
    
    
    public function __construct()
    {
      $this->leases = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set longitude
     *
     * @param decimal $longitude
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
    }

    /**
     * Get longitude
     *
     * @return decimal 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param decimal $latitude
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
    }

    /**
     * Get latitude
     *
     * @return decimal 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set street_address
     *
     * @param string $streetAddress
     */
    public function setStreetAddress($streetAddress)
    {
        $this->street_address = $streetAddress;
    }

    /**
     * Get street_address
     *
     * @return string 
     */
    public function getStreetAddress()
    {
        return $this->street_address;
    }

    /**
     * Set postal_code
     *
     * @param string $postalCode
     */
    public function setPostalCode($postalCode)
    {
        $this->postal_code = $postalCode;
    }

    /**
     * Get postal_code
     *
     * @return string 
     */
    public function getPostalCode()
    {
        return $this->postal_code;
    }

    /**
     * Set apartment_number
     *
     * @param string $apartmentNumber
     */
    public function setApartmentNumber($apartmentNumber)
    {
        $this->apartment_number = $apartmentNumber;
    }

    /**
     * Get apartment_number
     *
     * @return string 
     */
    public function getApartmentNumber()
    {
        return $this->apartment_number;
    }

    /**
     * Add leases
     *
     * @param Nimbus\ApartmentsBundle\Entity\Lease $leases
     */
    public function addLease(\Nimbus\ApartmentsBundle\Entity\Lease $leases)
    {
        $this->leases[] = $leases;
    }

    /**
     * Get leases
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getLeases()
    {
        return $this->leases;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }
}