<?php

namespace Nimbus\ApartmentsBundle\Entity;

use Nimbus\BaseBundle\Entity\Entity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="lease")
 */
class Lease extends Entity
{
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Apartment", inversedBy="lease", cascade={"all"})
     */
    protected $apartment;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $start_date;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $monthly_price;
    
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
     * Set start_date
     *
     * @param date $startDate
     */
    public function setStartDate($startDate)
    {
        $this->start_date = new \DateTime($startDate);
    }

    /**
     * Get start_date
     *
     * @return date 
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * Set monthly_price
     *
     * @param integer $monthlyPrice
     */
    public function setMonthlyPrice($monthlyPrice)
    {
        $this->monthly_price = $monthlyPrice;
    }

    /**
     * Get monthly_price
     *
     * @return integer 
     */
    public function getMonthlyPrice()
    {
        return $this->monthly_price;
    }
}