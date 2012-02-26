<?php

namespace Nimbus\ApartmentsBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ApartmentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ApartmentRepository extends EntityRepository
{
  public function getAllActive()
  {
      return $this->getEntityManager()
          ->createQuery(
            'SELECT a FROM NimbusApartmentsBundle:Apartment a 
              JOIN a.owner u
              WHERE u.enabled = 1'
            )
          ->getResult();
  }
}