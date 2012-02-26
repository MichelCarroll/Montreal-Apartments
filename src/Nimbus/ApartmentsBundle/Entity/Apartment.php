<?php

namespace Nimbus\ApartmentsBundle\Entity;

use Nimbus\BaseBundle\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use Nimbus\ApartmentsBundle\Helper\UrlHelper;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="apartment")
 * @ORM\Entity(repositoryClass="Nimbus\ApartmentsBundle\Repository\ApartmentRepository")
 */
class Apartment extends Entity implements Geolocatable
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
    
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id")
     */
    protected $owner;
    

    public function setTitle($title)
    {
      if ($this->slug == null) 
      {
        $this->slug = UrlHelper::slugify($title);
      }
      $this->title = $title;
    }
    
    /**
     * @ORM\Column(type="string", unique="true")
     */
    protected $slug;
    
    /**
     * @ORM\Column(type="decimal", scale=3, nullable="true")
     */
    protected $longitude;
    
    /**
     * @ORM\Column(type="decimal", scale=3, nullable="true")
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
     * @ORM\Column(type="string", nullable="true")
     */
    protected $apartment_number;

    /**
     * @ORM\Column(type="text", nullable="true")
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
     * @ORM\OneToOne(targetEntity="Lease", mappedBy="apartment", cascade={"all"})
     * @ORM\JoinColumn(name="lease_id", referencedColumnName="id", nullable=false)
     */
    protected $lease;
    
    public function getSummary()
    {
      return array(
        'title' => $this->title,
        'description' => $this->description
      );
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

    /**
     * Set lease
     *
     * @param Nimbus\ApartmentsBundle\Entity\Lease $lease
     */
    public function setLease(\Nimbus\ApartmentsBundle\Entity\Lease $lease)
    {
        $this->lease = $lease;
    }

    /**
     * Get lease
     *
     * @return Nimbus\ApartmentsBundle\Entity\Lease 
     */
    public function getLease()
    {
        return $this->lease;
    }

    /**
     * Set is_published
     *
     * @param boolean $isPublished
     */
    public function setIsPublished($isPublished)
    {
        $this->is_published = $isPublished;
    }

    /**
     * Get is_published
     *
     * @return boolean 
     */
    public function getIsPublished()
    {
        return $this->is_published;
    }

    /**
     * Set owner
     *
     * @param Nimbus\ApartmentsBundle\Entity\User $owner
     */
    public function setOwner(\Nimbus\ApartmentsBundle\Entity\User $owner)
    {
        $this->owner = $owner;
    }

    /**
     * Get owner
     *
     * @return Nimbus\ApartmentsBundle\Entity\User 
     */
    public function getOwner()
    {
        return $this->owner;
    }
}