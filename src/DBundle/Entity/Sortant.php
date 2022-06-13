<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Sortant
 *
 * @ORM\Table(name="sortant")
 * @ORM\Entity(repositoryClass="DBundle\Repository\SortantRepository")
 */
class Sortant
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
     * @ORM\Column(name="raison_social", type="string", length=255)
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
     * @ORM\Column(name="object_id", type="integer")
     */
    private $objectId;

    /**
     * @ORM\ManyToOne(targetEntity="Service")
     * @ORM\JoinColumn(nullable=true)
     */
    private $service;

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
     * @return Sortant
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
     * @return Sortant
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Sortant
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
     * @return Sortant
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
     * Set objectId
     *
     * @param integer $objectId
     *
     * @return Sortant
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;

        return $this;
    }

    /**
     * Get objectId
     *
     * @return integer
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * Set courrierId
     *
     * @param integer $courrierId
     *
     * @return Sortant
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
     * @param integer $yearCourr
     *
     * @return Sortant
     */
    public function setYearCourr($yearCourr)
    {
        $this->yearCourr = $yearCourr;

        return $this;
    }

    /**
     * Get yearCourr
     *
     * @return integer
     */
    public function getYearCourr()
    {
        return $this->yearCourr;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Sortant
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
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
     * Set objetCourrier
     *
     * @param string $objetCourrier
     *
     * @return Sortant
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
     * Set numeroCourrier
     *
     * @param integer $numeroCourrier
     *
     * @return Sortant
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

    /**
     * Set auteur
     *
     * @param \DBundle\Entity\User $auteur
     *
     * @return Sortant
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
     * Set gestionnaire
     *
     * @param \DBundle\Entity\User $gestionnaire
     *
     * @return Sortant
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
     * Set service
     *
     * @param \DBundle\Entity\Service $service
     *
     * @return Sortant
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
}
