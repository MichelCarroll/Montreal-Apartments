<?php

namespace Nimbus\ApartmentsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="lease")
 */
class Lease
{
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Apartment", inversedBy="leases")
     * @ORM\JoinColumn(name="apartment_id", referencedColumnName="id")
     */
    protected $apartment;

    /**
     * @ORM\Column(type="date")
     */
    protected $start_date;
    
    /**
     * @ORM\Column(type="integer")
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
        $this->start_date = $startDate;
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