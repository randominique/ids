<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Tache
 *
 * @ORM\Table(name="Tache")
 * @ORM\Entity(repositoryClass="DBundle\Repository\TacheRepository")
 */
class Tache
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
     * @ORM\Column(name="nif", type="string", length=11)
     */
    private $nif;

    /**
     * @var string
     *
     * @ORM\Column(name="rs", type="string", length=255)
     */
    private $rs;

    /**
     * @var int
     *
     * @ORM\Column(name="numero_courrier", type="integer")
     */
    private $numeroCourrier;

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
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $auteur;
    
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $gestionnaire;

    /**
     * @var \DateTime
     *
     * @Assert\NotBlank
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @Assert\NotBlank
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @Assert\NotBlank
     * @ORM\Column(name="delivered_date", type="datetime")
     */
    private $deliveredDate;

    
    /**
     * @ORM\ManyToOne(targetEntity="TacheObjet")
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
    * @Assert\NotBlank
    * @ORM\Column(name="tache_description", type="text")
    */
    private $tacheDescription;


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
     * Set priority
     *
     * @param string $priority
     *
     * @return Tache
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
     * @return Tache
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
     * Set tacheDescription
     *
     * @param string $tacheDescription
     *
     * @return Tache
     */
    public function setTacheDescription($tacheDescription)
    {
        $this->tacheDescription = $tacheDescription;

        return $this;
    }

    /**
     * Get tacheDescription
     *
     * @return string
     */
    public function getTacheDescription()
    {
        return $this->tacheDescription;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Tache
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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Tache
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
     * Set auteur
     *
     * @param \DBundle\Entity\User $auteur
     *
     * @return Tache
     */
    public function setAuteur(\DBundle\Entity\User $auteur = null)
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
     * Set object
     *
     * @param \DBundle\Entity\TacheObjet $object
     *
     * @return Tache
     */
    public function setObject(\DBundle\Entity\TacheObjet $object = null)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * Get object
     *
     * @return \DBundle\Entity\TacheObjet
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pourInfo = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add pourInfo
     *
     * @param \DBundle\Entity\PourInfo $pourInfo
     *
     * @return Tache
     */
    public function addPourInfo(\DBundle\Entity\PourInfo $pourInfo)
    {
        if (!$this->pourInfo->contains($pourInfo)) {
            $this->pourInfo[] = $pourInfo;
            $pourInfo->setCourrier($this);
        }

        return $this;

        // $this->pourInfo[] = $pourInfo;

        // return $this;
    }

    /**
     * Remove pourInfo
     *
     * @param \DBundle\Entity\PourInfo $pourInfo
     */
    public function removePourInfo(\DBundle\Entity\PourInfo $pourInfo)
    {
        $this->pourInfo->removeElement($pourInfo);
    }

    /**
     * Get pourInfo
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPourInfo()
    {
        return $this->pourInfo;
    }

    /**
     * Set service
     *
     * @param \DBundle\Entity\Service $service
     *
     * @return Tache
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
     * Set gestionnaire
     *
     * @param \DBundle\Entity\User $gestionnaire
     *
     * @return Tache
     */
    public function setGestionnaire(\DBundle\Entity\User $gestionnaire)
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
     * Set nif
     *
     * @param string $nif
     *
     * @return Tache
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
     * Set rs
     *
     * @param string $rs
     *
     * @return Tache
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
     * Set numeroCourrier
     *
     * @param integer $numeroCourrier
     *
     * @return Tache
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
