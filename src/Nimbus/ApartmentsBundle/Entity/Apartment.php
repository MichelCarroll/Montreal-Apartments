<?php

namespace Nimbus\ApartmentsBundle\Entity;

use Nimbus\BaseBundle\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use Nimbus\ApartmentsBundle\Helper\UrlHelper;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="apartment")
 * @ORM\Entity(repositoryClass="Nimbus\ApartmentsBundle\Repository\ApartmentRepository")
 * @ORM\HasLifecycleCallbacks
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
     * @ORM\Column(type="boolean")
     */
    protected $is_furnished = false;
    
    
    /**
     * @ORM\Column(type="string")
     */
    protected $size;
    
    
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
     * @ORM\Column(type="string", unique="true", nullable="true")
     */
    protected $slug;
    
    /**
     * @ORM\Column(type="decimal", scale=7, nullable="true")
     */
    protected $longitude;
    
    /**
     * @ORM\Column(type="decimal", scale=7, nullable="true")
     * @Assert\NotBlank(message="The apartment's location has to be entered")
     */
    protected $latitude;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $address;

    /**
     * @ORM\Column(type="text", nullable="true")
     */
    protected $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $image_path;
    
    
    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function preUpload()
    {
        if (null !== $this->file) {
            // do whatever you want to generate a unique name
            $this->image_path = uniqid().'.'.$this->file->guessExtension();
        }
    }
    
    /**
     * @ORM\PostRemove()
     */
    public function removeUpload()
    {
        if ($file = $this->getAbsolutePath()) {
            unlink($file);
        }
    }
    
    
    public function getAbsolutePath()
    {
        return null === $this->image_path ? null : $this->getUploadRootDir().'/'.$this->image_path;
    }

    public function getWebPath()
    {
        return null === $this->image_path ? null : $this->getUploadDir().'/'.$this->image_path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw when displaying uploaded doc/image in the view.
        return 'uploads/apartments';
    }
    
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
     * Set address
     *
     * @param string $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
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


    /**
     * Set path
     *
     * @param string $image_path
     */
    public function setImagePath($image_path)
    {
        $this->image_path = $image_path;
    }
    
    /**
     * @Assert\Image(maxSize="1M")
     */
    public $file;
    
    
    /**
     * @ORM\PostPersist()
     * @ORM\PostUpdate()
     */
    public function uploadPhoto()
    {
      if (null === $this->file) {
            return;
      }

      // if there is an error when moving the file, an exception will
      // be automatically thrown by move(). This will properly prevent
      // the entity from being persisted to the database on error
      $this->file->move($this->getUploadRootDir(), $this->image_path);
      
      unset($this->file);
    }
    
    

    /**
     * Set is_furnished
     *
     * @param boolean $isFurnished
     */
    public function setIsFurnished($isFurnished)
    {
        $this->is_furnished = $isFurnished;
    }

    /**
     * Get is_furnished
     *
     * @return boolean 
     */
    public function getIsFurnished()
    {
        return $this->is_furnished;
    }

    /**
     * Set size
     *
     * @param string $size
     */
    public function setSize($size)
    {
        $this->size = $size;
    }

    /**
     * Get size
     *
     * @return string 
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Get image_path
     *
     * @return string 
     */
    public function getImagePath()
    {
        return $this->image_path;
    }
}