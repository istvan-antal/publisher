<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \DateTime;

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
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private $webTrackingCode;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    private $deployType;
    
    /**
     * @var array
     *
     * @ORM\Column(type="json_array")
     */
    private $deploySettings;
    
    /**
     * @var DateTime
     *
     * @ORM\Column(type="datetime", options={"default"="CURRENT_TIMESTAMP"})
     */
    private $createdAt;
    
    public function __construct() {
        $this->createdAt = new \DateTime();
        $this->deploySettings = [];
    }

    public function getId() : int {
        return $this->id;
    }

    public function setName(string $name) : Site {
        $this->name = $name;

        return $this;
    }

    public function getName() : string {
        return $this->name;
    }

    public function setSiteTitle(string $siteTitle) : Site {
        $this->siteTitle = $siteTitle;

        return $this;
    }

    public function getSiteTitle() : string {
        return $this->siteTitle;
    }
    
    public function getWebTrackingCode() : string {
        return $this->webTrackingCode;
    }
    
    public function setWebTrackingCode(string $webTrackingCode) : Site {
        $this->webTrackingCode = $webTrackingCode;

        return $this;
    }
    
    public function getDeployType() : string {
        return $this->deployType;
    }
    
    public function setDeployType(string $deployType) : Site {
        $this->deployType = $deployType;

        return $this;
    }
    
    public function getDeploySettings() : array {
        return $this->deploySettings;
    }
    
    public function setDeploySettings(array $deploySettings) : Site {
        $this->deploySettings = $deploySettings;

        return $this;
    }

    public function setCreatedAt(DateTime $createdAt) : Site {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt() : DateTime {
        return $this->createdAt;
    }
    
    public function __toString() {
        return $this->getName();
    }
    
}