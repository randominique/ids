<?php

namespace DBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="im", type="string", length=255)
     */
    private $im;

    // /**
    //  * @var string
    //  *
    //  * @ORM\Column(name="username", type="string", length=255)
    //  */
    // protected $username;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    // /**
    //  * @var string
    //  *
    //  * @ORM\Column(name="password", type="string", length=255)
    //  */
    // protected $password;

    /**
     * @var string
     *
     * @ORM\Column(name="corps", type="string", length=255)
     */
    private $corps;

    /**
     * @var string
     *
     * @ORM\Column(name="inspecteur", type="boolean")
     */
    private $inspecteur;

    /**
     * @ORM\ManyToOne(targetEntity="DBundle\Entity\Service", inversedBy="users")
     * @ORM\JoinColumn(nullable=true)
     */
    private $service;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=255)
     */
    private $telephone;

    
    /**
     * @ORM\OneToMany(targetEntity="CourierEntrant", mappedBy="gestionaire")
     */
    private $courierEntrant;
    
    /**
     * @ORM\OneToMany(targetEntity="CourierEntrant", mappedBy="traitant")
     */
    private $doctraitee;
    
    /**
     * @ORM\OneToMany(targetEntity="CourierSortant", mappedBy="gestionaire")
     */
    private $courierSortant;

    /**
     * @ORM\OneToMany(targetEntity="SuiviDossiers", mappedBy="agent")
     */
    private $dossiers_enlever;

    /**
     * @ORM\ManyToMany(targetEntity=Entrant::class, inversedBy="Users")
     */
    private $entrant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var int
     *
     * @ORM\Column(name="nbrecourrier", type="integer", nullable=true)
     */
    private $nbrecourrier;

    /**
     * @var int
     *
     * @ORM\Column(name="nbretache", type="integer", nullable=true)
     */
    private $nbretache;


    public function __construct()
    {
        parent::__construct();
        $this->addRole("ROLE_USER");

        $this->courierEntrant = new ArrayCollection();
        $this->courierSortant = new ArrayCollection();
        $this->doctraitee = new ArrayCollection();
        $this->date = new \DateTime();
        $this->entrant = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString(){
        return $this->nom.' '. $this->prenom;
    }


    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    // /**
    //  * Set username
    //  *
    //  * @param string $username
    //  *
    //  * @return User
    //  */
    // public function setUsername($username)
    // {
    //     $this->username = $username;

    //     return $this;
    // }

    // /**
    //  * Get username
    //  *
    //  * @return string
    //  */
    // public function getUsername()
    // {
    //     return $this->username;
    // }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set corps
     *
     * @param string $corps
     *
     * @return User
     */
    public function setCorps($corps)
    {
        $this->corps = $corps;

        return $this;
    }

    /**
     * Get corps
     *
     * @return string
     */
    public function getCorps()
    {
        return $this->corps;
    }

    /**
     * Set inspecteur
     *
     * @param string $inspecteur
     *
     * @return User
     */
    public function setInspecteur($inspecteur)
    {
        $this->inspecteur = $inspecteur;

        return $this;
    }

    /**
     * Get inspecteur
     *
     * @return string
     */
    public function getInspecteur()
    {
        return $this->inspecteur;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return User
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set im
     *
     * @param string $im
     *
     * @return User
     */
    public function setIm($im)
    {
        $this->im = $im;

        return $this;
    }

    /**
     * Get im
     *
     * @return string
     */
    public function getIm()
    {
        return $this->im;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     *
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Add courierEntrant
     *
     * @param \DBundle\Entity\CourierEntrant $courierEntrant
     *
     * @return User
     */
    public function addCourierEntrant(\DBundle\Entity\CourierEntrant $courierEntrant)
    {
        $this->courierEntrant[] = $courierEntrant;

        return $this;
    }

    /**
     * Remove courierEntrant
     *
     * @param \DBundle\Entity\CourierEntrant $courierEntrant
     */
    public function removeCourierEntrant(\DBundle\Entity\CourierEntrant $courierEntrant)
    {
        $this->courierEntrant->removeElement($courierEntrant);
    }

    /**
     * Get courierEntrant
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCourierEntrant()
    {
        return $this->courierEntrant;
    }

    /**
     * Add courierSortant
     *
     * @param \DBundle\Entity\CourierSortant $courierSortant
     *
     * @return User
     */
    public function addCourierSortant(\DBundle\Entity\CourierSortant $courierSortant)
    {
        $this->courierSortant[] = $courierSortant;

        return $this;
    }

    /**
     * Remove courierSortant
     *
     * @param \DBundle\Entity\CourierSortant $courierSortant
     */
    public function removeCourierSortant(\DBundle\Entity\CourierSortant $courierSortant)
    {
        $this->courierSortant->removeElement($courierSortant);
    }

    /**
     * Get courierSortant
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCourierSortant()
    {
        return $this->courierSortant;
    }

    /**
     * Add doctraitee
     *
     * @param \DBundle\Entity\CourierEntrant $doctraitee
     *
     * @return User
     */
    public function addDoctraitee(\DBundle\Entity\CourierEntrant $doctraitee)
    {
        $this->doctraitee[] = $doctraitee;

        return $this;
    }

    /**
     * Remove doctraitee
     *
     * @param \DBundle\Entity\CourierEntrant $doctraitee
     */
    public function removeDoctraitee(\DBundle\Entity\CourierEntrant $doctraitee)
    {
        $this->doctraitee->removeElement($doctraitee);
    }

    /**
     * Get doctraitee
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDoctraitee()
    {
        return $this->doctraitee;
    }

    /**
     * Add dossiersEnlever
     *
     * @param \DBundle\Entity\SuiviDossiers $dossiersEnlever
     *
     * @return User
     */
    public function addDossiersEnlever(\DBundle\Entity\SuiviDossiers $dossiersEnlever)
    {
        $this->dossiers_enlever[] = $dossiersEnlever;

        return $this;
    }

    /**
     * Remove dossiersEnlever
     *
     * @param \DBundle\Entity\SuiviDossiers $dossiersEnlever
     */
    public function removeDossiersEnlever(\DBundle\Entity\SuiviDossiers $dossiersEnlever)
    {
        $this->dossiers_enlever->removeElement($dossiersEnlever);
    }

    /**
     * Get dossiersEnlever
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDossiersEnlever()
    {
        return $this->dossiers_enlever;
    }

    /**
     * Set service
     *
     * @param \DBundle\Entity\Service $service
     *
     * @return User
     */
    public function setService(\DBundle\Entity\Service $service)
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return User
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
     * Add entrant
     *
     * @param \DBundle\Entity\Entrant $entrant
     *
     * @return User
     */
    public function addEntrant(\DBundle\Entity\Entrant $entrant)
    {
        $this->entrant[] = $entrant;

        return $this;
    }

    /**
     * Remove entrant
     *
     * @param \DBundle\Entity\Entrant $entrant
     */
    public function removeEntrant(\DBundle\Entity\Entrant $entrant)
    {
        $this->entrant->removeElement($entrant);
    }

    /**
     * Get entrant
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEntrant()
    {
        return $this->entrant;
    }

    /**
     * Set nbrecourrier
     *
     * @param integer $nbrecourrier
     *
     * @return User
     */
    public function setNbrecourrier($nbrecourrier)
    {
        $this->nbrecourrier = $nbrecourrier;

        return $this;
    }

    /**
     * Get nbrecourrier
     *
     * @return int
     */
    public function getNbrecourrier()
    {
        return $this->nbrecourrier;
    }

    /**
     * Set nbretache
     *
     * @param integer $nbretache
     *
     * @return User
     */
    public function setNbretache($nbretache)
    {
        $this->nbretache = $nbretache;

        return $this;
    }

    /**
     * Get nbretache
     *
     * @return int
     */
    public function getNbretache()
    {
        return $this->nbretache;
    }

}
