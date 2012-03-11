<?php

namespace Nimbus\ApartmentsBundle\Entity;

use Nimbus\BaseBundle\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints as Recaptcha;

/**
 * @ORM\Entity
 * @ORM\Table(name="contact")
 */
class Contact extends Entity
{
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
      $this->sent_at = new \DateTime;
    }
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $name;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $email;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $message;
    
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="recipient_id", referencedColumnName="id")
     */
    protected $recipient;
    
    
    /**
     * @Recaptcha\True
     */
    public $recaptcha;
    
    
    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    public $sent_at;
    

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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set message
     *
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set sent_at
     *
     * @param datetime $sentAt
     */
    public function setSentAt($sentAt)
    {
        $this->sent_at = $sentAt;
    }

    /**
     * Get sent_at
     *
     * @return datetime 
     */
    public function getSentAt()
    {
        return $this->sent_at;
    }

    /**
     * Set apartment
     *
     * @param Nimbus\ApartmentsBundle\Entity\Apartment $apartment
     */
    public function setApartment(\Nimbus\ApartmentsBundle\Entity\Apartment $apartment)
    {
        $this->apartment = $apartment;
    }

    /**
     * Get apartment
     *
     * @return Nimbus\ApartmentsBundle\Entity\Apartment 
     */
    public function getApartment()
    {
        return $this->apartment;
    }

    /**
     * Set recipient
     *
     * @param Nimbus\ApartmentsBundle\Entity\User $recipient
     */
    public function setRecipient(\Nimbus\ApartmentsBundle\Entity\User $recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * Get recipient
     *
     * @return Nimbus\ApartmentsBundle\Entity\User 
     */
    public function getRecipient()
    {
        return $this->recipient;
    }
}