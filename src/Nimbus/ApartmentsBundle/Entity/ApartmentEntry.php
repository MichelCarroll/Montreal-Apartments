<?php

namespace Nimbus\ApartmentsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Nimbus\ApartmentsBundle\Helper\UrlHelper;

class ApartmentEntry
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
}