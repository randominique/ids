<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Missions
 *
 * @ORM\Table(name="Missions")
 * @ORM\Entity(repositoryClass="DBundle\Repository\MissionsRepository")
 */
class Missions
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
     * @var \DateTime
     *
     * @ORM\Column(name="created_At", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $auteur;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_At", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $gestionnaire;
    
    /**
     * @ORM\ManyToOne(targetEntity="MissionObjet")
     * @ORM\JoinColumn(nullable=false)
     */
    private $object;

    /**
     * @ORM\ManyToOne(targetEntity="Service")
     * @ORM\JoinColumn(nullable=true)
     */
    private $service;

    /**
    * @Assert\NotBlank
    */
    private $observationContent;

    /**
     * @var string
     *
     * @ORM\Column(name="rs", type="string", length=255, nullable=true)
     */
    private $rs;

    /**
     * @var string
     *
     * @ORM\Column(name="nif", type="string", length=255, nullable=true)
     */
    private $nif;

    /**
     * @var string
     *
     * @Assert\NotBlank
     * @ORM\Column(name="priority", type="string", length=255)
     */
    private $priority;

    /**
     * @var string
     *
     * @Assert\NotBlank
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @Assert\NotBlank
     * @ORM\Column(name="delivered_date", type="datetime")
     */
    private $deliveredDate;

    /**
    * @Assert\NotBlank
    * @ORM\Column(name="mission_description", type="text")
    */
    private $missionDescription;

    /**
     * @var int
     *
     * @ORM\Column(name="numero_courrier", type="integer")
     */
    private $numeroCourrier;

    public function __construct()
    {
        $this->createdAt = new \DateTime('NOW');
        
    }

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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Missions
     */
    public function setcreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getcreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set auteur
     *
     * @param \DBundle\Entity\User $auteur
     *
     * @return Missions
     */
    public function setAuteur(\DBundle\Entity\User $auteur)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return \DBundle\Entity\User
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Missions
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set gestionnaire
     *
     * @param \DBundle\Entity\User $gestionnaire
     *
     * @return Missions
     */
    public function setGestionnaire(\DBundle\Entity\User $gestionnaire = null)
    {
        $this->gestionnaire = $gestionnaire;

        return $this;
    }

    /**
     * Get gestionnaire
     *
     * @return \DBundle\Entity\User
     */
    public function getGestionnaire()
    {
        return $this->gestionnaire;
    }

    /**
     * Set object
     *
     * @param string $object
     *
     * @return Missions
     */
    public function setObject($object)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * Get object
     *
     * @return string
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Set service
     *
     * @param \DBundle\Entity\Service $service
     *
     * @return Missions
     */
    public function setService(\DBundle\Entity\Service $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \DBundle\Entity\Service
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set ObservationContent
     *
     * @param string $ObservationContent
     *
     * @return Tache
     */
    public function setObservationContent($observationContent)
    {
        $this->observationContent = $observationContent;

        return $this;
    }

    /**
     * Get observationContent
     *
     * @return string
     */
    public function getObservationContent()
    {
        return $this->observationContent;
    }

    /**
     * Set rs
     *
     * @param string $rs
     *
     * @return Missions
     */
    public function setRs($rs)
    {
        $this->rs = $rs;

        return $this;
    }

    /**
     * Get rs
     *
     * @return string
     */
    public function getRs()
    {
        return $this->rs;
    }

    /**
     * Set nif
     *
     * @param string $nif
     *
     * @return Missions
     */
    public function setNif($nif)
    {
        $this->nif = $nif;

        return $this;
    }

    /**
     * Get nif
     *
     * @return string
     */
    public function getNif()
    {
        return $this->nif;
    }

    /**
     * Set priority
     *
     * @param string $priority
     *
     * @return Missions
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority
     *
     * @return string
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Missions
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
     * Set deliveredDate
     *
     * @param \DateTime $deliveredDate
     *
     * @return Tache
     */
    public function setDeliveredDate($deliveredDate)
    {
        $this->deliveredDate = $deliveredDate;

        return $this;
    }

    /**
     * Get deliveredDate
     *
     * @return \DateTime
     */
    public function getDeliveredDate()
    {
        return $this->deliveredDate;
    }

    /**
     * Set missionDescription
     *
     * @param string $missionDescription
     *
     * @return Missions
     */
    public function setMissionDescription($missionDescription)
    {
        $this->missionDescription = $missionDescription;

        return $this;
    }

    /**
     * Get missionDescription
     *
     * @return string
     */
    public function getMissionDescription()
    {
        return $this->missionDescription;
    }

    /**
     * Set numeroCourrier
     *
     * @param integer $numeroCourrier
     *
     * @return Missions
     */
    public function setNumeroCourrier($numeroCourrier)
    {
        $this->numeroCourrier = $numeroCourrier;

        return $this;
    }

    /**
     * Get numeroCourrier
     *
     * @return integer
     */
    public function getNumeroCourrier()
    {
        return $this->numeroCourrier;
    }

}
