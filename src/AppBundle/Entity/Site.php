<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Site {
    
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $siteTitle;
    
    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt;
    
    public function __construct() {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param string $name
     *
     * @return Content
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName() {
        return $this->name;
    }
    
    /**
     * @param string $siteTitle
     *
     * @return Content
     */
    public function setSiteTitle($siteTitle) {
        $this->siteTitle = $siteTitle;

        return $this;
    }

    /**
     * @return string
     */
    public function getSiteTitle() {
        return $this->siteTitle;
    }
    
    /**
     * @param \DateTime $createdAt
     *
     * @return Account
     */
    public function setCreatedAt($createdAt) {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt() {
        return $this->createdAt;
    }
    
    public function __toString() {
        return $this->getName();
    }
    
}