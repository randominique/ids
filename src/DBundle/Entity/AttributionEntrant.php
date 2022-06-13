<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Entrant
 *
 * @ORM\Table(name="Entrant")
 * @ORM\Entity(repositoryClass="DBundle\Repository\EntrantRepository")
 */
class AttributionEntrant
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
     * 
     * @ORM\Column(name="raison_social", type="string", length=255, nullable=true)
     */
    private $raisonSocial;

    /**
     * @var string
     *
     * 
     * @ORM\Column(name="nif", type="string", length=255)
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
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $auteur;
    
    // /**
    //  * @ORM\ManyToOne(targetEntity="User")
    //  * @ORM\JoinColumn(nullable=true)
    //  */
    // private $gestionnaire;

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
     * @ORM\Column(name="object_id", type="integer", nullable=true)
     */
    private $objectId;

    // /**
    //  * @ORM\ManyToOne(targetEntity="Service")
    //  * @ORM\JoinColumn(nullable=true)
    //  */
    // private $service;

    /**
     * @ORM\OneToMany(targetEntity="PourInfo", mappedBy="courrier", cascade={"persist"})
     */
    private $pourInfo;


    /**
    * @Assert\NotBlank
    */
    private $observationContent;


    /**
    * @var CourrierDispatching
    
    * @ORM\OneToMany(targetEntity="DBundle\Entity\CourrierDispatching", mappedBy="docNo")
    */
    public $dispatchings;

    /**
     * 
     * @ORM\Column(name="courrier_id", type="integer")
     */
    private $courrierId;

    /**
     * @var int
     * 
     *  @ORM\Column(name="year_courr", type="integer")
     */
    private $yearCourr;

    /**
     * @var string
     * 
     * @ORM\Column(name="titre", type="string")
     */
    private $titre;

    /**
     * @var string
     * 
     * @ORM\Column(name="objet", type="string")
     */
    private $objetCourrier;

    /**
     * @var int
     * 
     * @ORM\Column(name="numero_courrier", type="integer")
     */
    private $numeroCourrier;

    /**
     * @var DateTime
     * 
     * @ORM\Column(name="delegation_date", type="datetime",nullable=true)
     */
    private $delegationDate;

    /**
     * @var DateTime
     * 
     * @ORM\Column(name="Traitement_date", type="datetime",nullable=true)
     */
    private $traitementDate;

    /**
     * @var string
     *
     *  @ORM\Column(name="attribution", type="string")
     */
    private $attribution;

    /**
     * @var string
     * 
     *  @ORM\Column(name="commentaires", type="string",nullable=true)
     */
    private $commentaires;

    /**
     * @ORM\ManyToMany(targetEntity=Service::class, mappedBy="entrant")
     */
    private $services;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="entrant")
     */
    private $gestionnaires;

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
     * Set raisonSocial
     *
     * @param string $raisonSocial
     *
     * @return Entrant
     */
    public function setRaisonSocial($raisonSocial)
    {
        $this->raisonSocial = $raisonSocial;

        return $this;
    }

    /**
     * Get raisonSocial
     *
     * @return string
     */
    public function getRaisonSocial()
    {
        return $this->raisonSocial;
    }

    /**
     * Set nif
     *
     * @param string $nif
     *
     * @return Entrant
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
     * @return Entrant
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
     * @return Entrant
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
     * @return Entrant
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Entrant
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
     * @return Entrant
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
     * Set auteur
     *
     * @param \DBundle\Entity\User $auteur
     *
     * @return Entrant
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
     * Set objectId
     *
     * @param int
     *
     * @return Entrant
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;

        return $this;
    }

    /**
     * Get objectId
     *
     * @return int
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pourInfo = new \Doctrine\Common\Collections\ArrayCollection();
        $this->services = new \Doctrine\Common\Collections\ArrayCollection();
        $this->gestionnaires = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add pourInfo
     *
     * @param \DBundle\Entity\PourInfo $pourInfo
     *
     * @return Entrant
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

    // /**
    //  * Set service
    //  *
    //  * @param \DBundle\Entity\Service $service
    //  *
    //  * @return Entrant
    //  */
    // public function setService(\DBundle\Entity\Service $service = null)
    // {
    //     $this->service = $service;

    //     return $this;
    // }

    // /**
    //  * Get service
    //  *
    //  * @return \DBundle\Entity\Service
    //  */
    // public function getService()
    // {
    //     return $this->service;
    // }

    // /**
    //  * Set gestionnaire
    //  *
    //  * @param \DBundle\Entity\User $gestionnaire
    //  *
    //  * @return Entrant
    //  */
    // public function setGestionnaire(\DBundle\Entity\User $gestionnaire)
    // {
    //     $this->gestionnaire = $gestionnaire;

    //     return $this;
    // }

    // /**
    //  * Get gestionnaire
    //  *
    //  * @return \DBundle\Entity\User
    //  */
    // public function getGestionnaire()
    // {
    //     return $this->gestionnaire;
    // }


    /**
     * Set courrierId
     *
     * @param integer $courrierId
     *
     * @return DocCourrier
     */
    public function setCourrierId($courrierId)
    {
        $this->courrierId = $courrierId;

        return $this;
    }

    /**
     * Get courrierId
     *
     * @return integer
     */
    public function getCourrierId()
    {
        return $this->courrierId;
    }


    /**
     * Set yearCourr
     *
     * @param int
     *
     * @return Entrant
     */
    public function setYearCourr($yearCourr)
    {
        $this->yearCourr = $yearCourr;

        return $this;
    }

    /**
     * Get yearCourr
     *
     * @return int
     */
    public function getYearCourr()
    {
        return $this->yearCourr;
    }

    /**
     * Get titre
     * 
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set titre
     * 
     * @return Entrant
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get objet
     * 
     * @return string
     */
    public function getObjet()
    {
        return $this->objetCourrier;
    }

    /**
     * Set objet
     * 
     * @return Entrant
     */
    public function setObjet($objetCourrier)
    {
        $this->objetCourrier = $objetCourrier;

        return $this;
    }
    /**
     * Set numeroCourrier
     *
     * @param int
     *
     * @return Entrant
     */
    public function setNumeroCourrier($numeroCourrier)
    {
        $this->numeroCourrier = $numeroCourrier;

        return $this;
    }

    /**
     * Get numeroCourrier
     *
     * @return int
     */
    public function getNumeroCourrier()
    {
        return $this->numeroCourrier;
    }

    /**
     * Set objetCourrier
     *
     * @param string $objetCourrier
     *
     * @return Entrant
     */
    public function setObjetCourrier($objetCourrier)
    {
        $this->objetCourrier = $objetCourrier;

        return $this;
    }

    /**
     * Get objetCourrier
     *
     * @return string
     */
    public function getObjetCourrier()
    {
        return $this->objetCourrier;
    }

    /**
     * Set delegationDate
     *
     * @param \DateTime $delegationDate
     *
     * @return Entrant
     */
    public function setDelegationDate($delegationDate)
    {
        $this->delegationDate = $delegationDate;

        return $this;
    }

    /**
     * Get delegationDate
     *
     * @return \DateTime
     */
    public function getDelegationDate()
    {
        return $this->delegationDate;
    }

    /**
     * Set traitementDate
     *
     * @param \DateTime $traitementDate
     *
     * @return Entrant
     */
    public function setTraitementDate($traitementDate)
    {
        $this->traitementDate = $traitementDate;

        return $this;
    }

    /**
     * Get traitementDate
     *
     * @return \DateTime
     */
    public function getTraitementDate()
    {
        return $this->traitementDate;
    }

    /**
     * Add dispatching
     *
     * @param \DBundle\Entity\CourrierDispatching $dispatching
     *
     * @return Entrant
     */
    public function addDispatching(\DBundle\Entity\CourrierDispatching $dispatching)
    {
        $this->dispatchings[] = $dispatching;

        return $this;
    }

    /**
     * Remove dispatching
     *
     * @param \DBundle\Entity\CourrierDispatching $dispatching
     */
    public function removeDispatching(\DBundle\Entity\CourrierDispatching $dispatching)
    {
        $this->dispatchings->removeElement($dispatching);
    }

    /**
     * Get dispatchings
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDispatchings()
    {
        return $this->dispatchings;
    }

    /**
     * Set attribution
     *
     * @param string $attribution
     *
     * @return Entrant
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
     * Set commentaires
     *
     * @param string $commentaires
     *
     * @return Entrant
     */
    public function setCommentaires($commentaires)
    {
        $this->commentaires = $commentaires;

        return $this;
    }

    /**
     * Get commentaires
     *
     * @return string
     */
    public function getcommentaires()
    {
        return $this->commentaires;
    }

    /**
     * Add service
     *
     * @param \DBundle\Entity\Service $service
     *
     * @return Entrant
     */
    public function addService(\DBundle\Entity\Service $service)
    {
        $this->services[] = $service;
        $service->addEntrant($this);
        return $this;
    }

    /**
     * Remove service
     *
     * @param \DBundle\Entity\Service $service
     */
    public function removeService(\DBundle\Entity\Service $service)
    {
        $this->services->removeElement($service);
    }

    /**
     * Get services
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getServices()
    {
        return $this->services;
    }

    /**
     * Add gestionnaire
     *
     * @param \DBundle\Entity\User $gestionnaire
     *
     * @return Entrant
     */
    public function addGestionnaire(\DBundle\Entity\User $gestionnaire)
    {
        $this->gestionnaires[] = $gestionnaire;
        $gestionnaire->addEntrant($this);
        return $this;
    }

    /**
     * Remove gestionnaire
     *
     * @param \DBundle\Entity\User $gestionnaire
     */
    public function removeGestionnaire(\DBundle\Entity\User $gestionnaire)
    {
        $this->gestionnaires->removeElement($gestionnaire);
    }

    /**
     * Get gestionnaires
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGestionnaires()
    {
        return $this->gestionnaires;
    }
}
