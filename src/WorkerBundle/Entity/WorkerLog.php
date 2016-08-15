<?php

namespace WorkerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class WorkerLog {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50, options={"default"="success"})
     */
    private $status = self::STATUS_WORKING;
    
    const STATUS_WORKING = 'working';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILURE = 'failure';
    
    /**
     * @var string
     *
     * @ORM\Column(type="string", length=50)
     */
    protected $type;
    
    /**
     * Elapsed time(in seconds).
     *
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $elapsedTime = 0;
    
    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $errorMessage;

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
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }
    
    /**
     * @param string $status
     * @return \Data\Entity\WorkerLog
     */
    public function setStatus(string $status) {
        $this->status = $status;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getType() {
        return $this->type;
    }
    
    /**
     * @param string $type
     * @return \Data\Entity\WorkerLog
     */
    public function setType(string $type) {
        $this->type = $type;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getErrorMessage() {
        return $this->errorMessage;
    }
    
    /**
     * @param string $errorMessage
     * @return \Data\Entity\WorkerLog
     */
    public function setErrorMessage(string $errorMessage) {
        $this->errorMessage = $errorMessage;
        return $this;
    }
    
    /**
     * @param integer $elapsedTime
     *
     * @return Content
     */
    public function setElapsedTime($elapsedTime) {
        $this->elapsedTime = $elapsedTime;

        return $this;
    }

    /**
     * @return integer
     */
    public function getElapsedTime() {
        return $this->elapsedTime;
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

}
