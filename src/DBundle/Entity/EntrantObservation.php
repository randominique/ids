<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * EntrantObservation
 *
 * @ORM\Table(name="EntrantObservation")
 * @ORM\Entity(repositoryClass="DBundle\Repository\EntrantObservationRepository")
 */
class EntrantObservation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(name="entrantIdAuto",nullable=true)
     */
    private $entrantIdAuto;

    /**
     * @ORM\Column(name="courrier_id",nullable=false)
     */
    private $courrier;

    /**
     * @var string
     *
     *  @ORM\Column(name="status", type="string")
     */
    private $status;

    /**
     * @var string
     *
     *  @ORM\Column(name="service", type="string")
     */
    private $service;

    /**
     * @var string
     *
     *  @ORM\Column(name="attribution", type="string")
     */
    private $attribution;

    /**
     * @var string
     *
     * @ORM\Column(name="observations", type="text")
     */
    private $observations;

    /**
     * @var int
     *
     * @ORM\Column(name="dispatch", type="integer")
     */
    private $dispatch;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return EntrantObservation
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return EntrantObservation
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set user
     *
     * @param \DBundle\Entity\User $user
     *
     * @return EntrantObservation
     */
    public function setUser(\DBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \DBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set entrantIdAuto
     *
     * @param $entrantIdAuto
     *
     * @return EntrantObservation
     */
    public function setEntrantIdAuto($entrantIdAuto)
    {
        $this->entrantIdAuto = $entrantIdAuto;

        return $this;
    }

    /**
     * Get entrantIdAuto
     *
     * @return 
     */
    public function getEntrantIdAuto()
    {
        return $this->entrantIdAuto;
    }

    /**
     * Set courrier
     *
     * @param $courrier
     *
     * @return EntrantObservation
     */
    public function setCourrier($courrier)
    {
        $this->courrier = $courrier;

        return $this;
    }

    /**
     * Get courrier
     *
     * @return 
     */
    public function getCourrier()
    {
        return $this->courrier;
    }

        /**
     * Set status
     *
     * @param string $status
     *
     * @return EntrantObservation
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set service
     *
     * @param string $service
     *
     * @return EntrantObservation
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return string
     */
    public function getService()
    {
        return $this->service;
    }
    

    /**
     * Set attribution
     *
     * @param string $attribution
     *
     * @return EntrantObservation
     */
    public function setAttribution($attribution)
    {
        $this->attribution = $attribution;

        return $this;
    }

    /**
     * Get attribution
     *
     * @return string
     */
    public function getAttribution()
    {
        return $this->attribution;
    }

    /**
     * Set observations
     *
     * @param string $observations
     *
     * @return EntrantObservation
     */
    public function setObservations($observations)
    {
        $this->observations = $observations;

        return $this;
    }

    /**
     * Get observations
     *
     * @return string
     */
    public function getObservations()
    {
        return $this->observations;
    }
    /**
     * Set dispatch
     *
     * @param int $dispatch
     *
     * @return EntrantObservation
     */
    public function setDispatch($dispatch)
    {
        $this->dispatch = $dispatch;

        return $this;
    }

    /**
     * Get dispatch
     *
     * @return int
     */
    public function getDispatch()
    {
        return $this->dispatch;
    }

}
