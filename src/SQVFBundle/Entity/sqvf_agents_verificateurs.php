<?php

namespace SQVFBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * sqvf_agents_verificateurs
 *
 * @ORM\Table(name="sqvf_agents_verificateurs")
 * @ORM\Entity(repositoryClass="SQVFBundle\Repository\sqvf_agents_verificateursRepository")
 */
class sqvf_agents_verificateurs
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
     * @ORM\Column(name="id_poste", type="integer", nullable=true)
     */
    private $idPoste;

    /**
     * @var string
     *
     * @ORM\Column(name="matricule", type="string", length=11, nullable=true)
     */
    private $matricule;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=250, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="bureau", type="string", length=50, nullable=true)
     */
    private $bureau;

    /**
     * @var string
     *
     * @ORM\Column(name="code_bureau", type="string", length=50, nullable=true)
     */
    private $codeBureau;

    /**
     * @var string
     *
     * @ORM\Column(name="service", type="string", length=50, nullable=true)
     */
    private $service;

    /**
     * @var int
     *
     * @ORM\Column(name="telephone", type="integer", nullable=true)
     */
    private $telephone;


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
     * Set idPoste
     *
     * @param integer $idPoste
     *
     * @return sqvf_agents_verificateurs
     */
    public function setIdPoste($idPoste)
    {
        $this->idPoste = $idPoste;

        return $this;
    }

    /**
     * Get idPoste
     *
     * @return int
     */
    public function getIdPoste()
    {
        return $this->idPoste;
    }

    /**
     * Set matricule
     *
     * @param string $matricule
     *
     * @return sqvf_agents_verificateurs
     */
    public function setMatricule($matricule)
    {
        $this->matricule = $matricule;

        return $this;
    }

    /**
     * Get matricule
     *
     * @return string
     */
    public function getMatricule()
    {
        return $this->matricule;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return sqvf_agents_verificateurs
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

    /**
     * Set bureau
     *
     * @param string $bureau
     *
     * @return sqvf_agents_verificateurs
     */
    public function setBureau($bureau)
    {
        $this->bureau = $bureau;

        return $this;
    }

    /**
     * Get bureau
     *
     * @return string
     */
    public function getBureau()
    {
        return $this->bureau;
    }

    /**
     * Set codeBureau
     *
     * @param string $codeBureau
     *
     * @return sqvf_agents_verificateurs
     */
    public function setCodeBureau($codeBureau)
    {
        $this->codeBureau = $codeBureau;

        return $this;
    }

    /**
     * Get codeBureau
     *
     * @return string
     */
    public function getCodeBureau()
    {
        return $this->codeBureau;
    }

    /**
     * Set service
     *
     * @param string $service
     *
     * @return sqvf_agents_verificateurs
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
     * Set telephone
     *
     * @param integer $telephone
     *
     * @return sqvf_agents_verificateurs
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return int
     */
    public function getTelephone()
    {
        return $this->telephone;
    }
}

