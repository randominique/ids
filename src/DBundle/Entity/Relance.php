<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Relance
 *
 * @ORM\Table(name="relance")
 * @ORM\Entity(repositoryClass="DBundle\Repository\RelanceRepository")
 */
class Relance
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
     * @var int
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="nif", type="string", length=255)
     */
    private $nif;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="ville", type="string", length=255)
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="impotsConcernes", type="string", length=255)
     */
    private $impotsConcernes;

    /**
     * @var string
     *
     * @ORM\Column(name="periodeConcernes", type="string", length=255)
     */
    private $periodeConcernes;

    /**
     * @var string
     *
     * @ORM\Column(name="objet", type="string", length=255)
     */
    private $objet;

    /**
     * @var string
     *
     * @ORM\Column(name="droitApplicable", type="string", length=255)
     */
    private $droitApplicable;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateAccuse", type="datetime", nullable=true)
     */
    private $dateAccuse;

    /**
     * @var string
     *
     * @ORM\Column(name="nomReceptionnaire", type="string", length=255, nullable=true)
     */
    private $nomReceptionnaire;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function __construct(){
        $this->date = new \DateTime();
    }

    /**
     * Set type
     *
     * @param integer $type
     *
     * @return Relance
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Relance
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set nif
     *
     * @param string $nif
     *
     * @return Relance
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
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Relance
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set ville
     *
     * @param string $ville
     *
     * @return Relance
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set impotsConcernes
     *
     * @param string $impotsConcernes
     *
     * @return Relance
     */
    public function setImpotsConcernes($impotsConcernes)
    {
        $this->impotsConcernes = $impotsConcernes;

        return $this;
    }

    /**
     * Get impotsConcernes
     *
     * @return string
     */
    public function getImpotsConcernes()
    {
        return $this->impotsConcernes;
    }

    /**
     * Set periodeConcernes
     *
     * @param string $periodeConcernes
     *
     * @return Relance
     */
    public function setPeriodeConcernes($periodeConcernes)
    {
        $this->periodeConcernes = $periodeConcernes;

        return $this;
    }

    /**
     * Get periodeConcernes
     *
     * @return string
     */
    public function getPeriodeConcernes()
    {
        return $this->periodeConcernes;
    }

    /**
     * Set objet
     *
     * @param string $objet
     *
     * @return Relance
     */
    public function setObjet($objet)
    {
        $this->objet = $objet;

        return $this;
    }

    /**
     * Get objet
     *
     * @return string
     */
    public function getObjet()
    {
        return $this->objet;
    }

    /**
     * Set droitApplicable
     *
     * @param string $droitApplicable
     *
     * @return Relance
     */
    public function setDroitApplicable($droitApplicable)
    {
        $this->droitApplicable = $droitApplicable;

        return $this;
    }

    /**
     * Get droitApplicable
     *
     * @return string
     */
    public function getDroitApplicable()
    {
        return $this->droitApplicable;
    }

    /**
     * Set dateAccuse
     *
     * @param \DateTime $dateAccuse
     *
     * @return Relance
     */
    public function setDateAccuse($dateAccuse)
    {
        $this->dateAccuse = $dateAccuse;

        return $this;
    }

    /**
     * Get dateAccuse
     *
     * @return \DateTime
     */
    public function getDateAccuse()
    {
        return $this->dateAccuse;
    }

    /**
     * Set nomReceptionnaire
     *
     * @param string $nomReceptionnaire
     *
     * @return Relance
     */
    public function setNomReceptionnaire($nomReceptionnaire)
    {
        $this->nomReceptionnaire = $nomReceptionnaire;

        return $this;
    }

    /**
     * Get nomReceptionnaire
     *
     * @return string
     */
    public function getNomReceptionnaire()
    {
        return $this->nomReceptionnaire;
    }

    /**
     * Set user
     *
     * @param \DBundle\Entity\User $user
     *
     * @return Relance
     */
    public function setUser(\DBundle\Entity\User $user)
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
}
