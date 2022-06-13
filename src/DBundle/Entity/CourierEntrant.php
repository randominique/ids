<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CourierEntrant
 *
 * @ORM\Table(name="courier_entrant")
 * @ORM\Entity(repositoryClass="DBundle\Repository\CourierEntrantRepository")
 */
class CourierEntrant
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
     * @ORM\Column(name="ref", type="string", length=255)
     */
    private $ref;

    /**
     * @var string
     *
     * @ORM\Column(name="objet", type="string", length=255)
     */
    private $objet;
    
    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;
    
    /**
     * @ORM\ManyToOne(targetEntity="CategorieCourierEntrant", inversedBy="couriers")
     * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id")
     */
    private $categorie;
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="courierEntrant")
     * @ORM\JoinColumn(nullable=true)
     */
    private $gestionaire;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="doctraitee")
     * @ORM\JoinColumn(nullable=true)
     */
    private $traitant;
    
    /**
     * @var string
     *
     * @ORM\Column(name="etat", type="string", length=255, nullable=true)
     */
    private $etat;
    
    /**
     * @var string
     *
     * @ORM\Column(name="nif", type="string", length=255, nullable=true)
     */
    private $nif;
    
    /**
     * @var string
     *
     * @ORM\Column(name="rs", type="string", length=255)
     */
    private $rs;
    
    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     */
    private $adresse;
    
    /**
     * @var string
     *
     * @ORM\Column(name="observation", type="text", nullable=true)
     */
    private $observation;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    private $contribuable;
    

    // /**
    //  * @var CourrierDispatching
    //  * @ORM\OneToMany(targetEntity="DBundle\Entity\CourrierDispatching", mappedBy="docNo")
    //  */
    public $dispatchings;
    
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
     * Set ref
     *
     * @param string $ref
     *
     * @return CourierEntrant
     */
    public function setRef($ref)
    {
        $this->ref = $ref;

        return $this;
    }

    /**
     * Get ref
     *
     * @return string
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * Set $contribuable
     *
     * @param string $contribuable
     *
     * @return CourierEntrant
     */
    public function setContribuable($contribuable)
    {
        
        $this->contribuable = $contribuable;
        return $this;
    }
    
    /**
     * Get $contribuable
     *
     * @return string
     */
    public function getContribuable()
    {
        return $this->contribuable;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return CourierEntrant
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
     * Set categorie
     *
     * @param \DBundle\Entity\CategorieCourierEntrant $categorie
     *
     * @return CourierEntrant
     */
    public function setCategorie(\DBundle\Entity\CategorieCourierEntrant $categorie = null)
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \DBundle\Entity\CategorieCourierEntrant
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set objet
     *
     * @param string $objet
     *
     * @return CourierEntrant
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
     * Set titre
     *
     * @param string $titre
     *
     * @return CourierEntrant
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
     * Set nif
     *
     * @param string $nif
     *
     * @return CourierEntrant
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
     * @return CourierEntrant
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
     * Set adresse
     *
     * @param string $adresse
     *
     * @return CourierEntrant
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
     * Set observation
     *
     * @param string $observation
     *
     * @return CourierEntrant
     */
    public function setObservation($observation)
    {
        $this->observation = $observation;

        return $this;
    }

    /**
     * Get observation
     *
     * @return string
     */
    public function getObservation()
    {
        return $this->observation;
    }

    /**
     * Set gestionaire
     *
     * @param \DBundle\Entity\User $gestionaire
     *
     * @return CourierEntrant
     */
    public function setGestionaire(\DBundle\Entity\User $gestionaire)
    {
        $this->gestionaire = $gestionaire;

        return $this;
    }

    /**
     * Get gestionaire
     *
     * @return \DBundle\Entity\User
     */
    public function getGestionaire()
    {
        return $this->gestionaire;
    }

    /**
     * Set etat
     *
     * @param string $etat
     *
     * @return CourierEntrant
     */
    public function setEtat($etat)
    {
        $this->etat = $etat;

        return $this;
    }

    /**
     * Get etat
     *
     * @return string
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * Set traitant
     *
     * @param \DBundle\Entity\User $traitant
     *
     * @return CourierEntrant
     */
    public function setTraitant(\DBundle\Entity\User $traitant = null)
    {
        $this->traitant = $traitant;

        return $this;
    }

    /**
     * Get traitant
     *
     * @return \DBundle\Entity\User
     */
    public function getTraitant()
    {
        return $this->traitant;
    }
}
