<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Contribuables
 *
 * @ORM\Table(name="Contribuables")
 * @ORM\Entity(repositoryClass="DBundle\Repository\ContribuablesRepository")
 */
class Contribuables
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
     * @ORM\Column(name="nif", type="string", length=255)
     */
    private $nif;

    /**
     * @var int
     *
     * @ORM\Column(name="taxPayerNo", type="integer")
     */
    private $taxPayerNo;

    /**
     * @var string
     *
     * @ORM\Column(name="RAISON_SOCIALE", type="string", length=255)
     */
    private $raisonSociale;

    /**
     * @var string
     *
     * @ORM\Column(name="nomCommercial", type="string", length=255, nullable=true)
     */
    private $nomCommercial;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="telephone", type="string", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @var string
     *
     * @ORM\Column(name="regimeFiscal", type="string", length=255, nullable=true)
     */
    private $regimeFiscal;

    /**
     * @var string
     *
     * @ORM\Column(name="secteurActivite", type="string", length=255, nullable=true)
     */
    private $secteurActivite;

    /**
     * @var string
     *
     * @ORM\Column(name="nomDirigeant", type="string", length=255, nullable=true)
     */
    private $nomDirigeant;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreation", type="date", nullable=true)
     */
    private $dateCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateArriveeDGE", type="date", nullable=true)
     */
    private $dateArriveeDGE;

    /**
     * @var int
     *
     * @ORM\Column(name="exerciceFiscalDebut", type="integer", nullable=true)
     */
    private $exerciceFiscalDebut;

    /**
     * @var int
     *
     * @ORM\Column(name="exerciceFiscalFin", type="integer", nullable=true)
     */
    private $exerciceFiscalFin;

    /**
     * @var string
     *
     * @ORM\Column(name="gestionnaire", type="string", length=255, nullable=true)
     */
    private $gestionnaire;

    /**
     * @var date
     *
     * @ORM\Column(name="inactifDate", type="date", nullable=true)
     */
    private $inactifDate;


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
     * Set nif
     *
     * @param string $nif
     *
     * @return Contribuables
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
     * Set taxPayerNo
     *
     * @param integer $taxPayerNo
     *
     * @return Contribuables
     */
    public function setTaxpayerNo($taxPayerNo)
    {
        $this->taxPayerNo = $taxPayerNo;

        return $this;
    }

    /**
     * Get taxPayerNo
     *
     * @return int
     */
    public function getTaxpayerNo()
    {
        return $this->taxPayerNo;
    }

    /**
     * Set raisonSociale
     *
     * @param string $raisonSociale
     *
     * @return Contribuables
     */
    public function setRaisonSociale($raisonSociale)
    {
        $this->raisonSociale = $raisonSociale;

        return $this;
    }

    /**
     * Get raisonSociale
     *
     * @return string
     */
    public function getRaisonSociale()
    {
        return $this->raisonSociale;
    }

    /**
     * Set nomCommercial
     *
     * @param string $nomCommercial
     *
     * @return Contribuables
     */
    public function setNomCommercial($nomCommercial)
    {
        $this->nomCommercial = $nomCommercial;

        return $this;
    }

    /**
     * Get nomCommercial
     *
     * @return string
     */
    public function getNomCommercial()
    {
        return $this->nomCommercial;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Contribuables
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
     * Set email
     *
     * @param string $email
     *
     * @return Contribuables
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Contribuables
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
     * Set regimeFiscal
     *
     * @param string $regimeFiscal
     *
     * @return Contribuables
     */
    public function setRegimeFiscal($regimeFiscal)
    {
        $this->regimeFiscal = $regimeFiscal;

        return $this;
    }

    /**
     * Get regimeFiscal
     *
     * @return string
     */
    public function getRegimeFiscal()
    {
        return $this->regimeFiscal;
    }

    /**
     * Set secteurActivite
     *
     * @param string $secteurActivite
     *
     * @return Contribuables
     */
    public function setSecteurActivite($secteurActivite)
    {
        $this->secteurActivite = $secteurActivite;

        return $this;
    }

    /**
     * Get secteurActivite
     *
     * @return string
     */
    public function getSecteurActivite()
    {
        return $this->secteurActivite;
    }

    /**
     * Set nomDirigeant
     *
     * @param string $nomDirigeant
     *
     * @return Contribuables
     */
    public function setNomDirigeant($nomDirigeant)
    {
        $this->nomDirigeant = $nomDirigeant;

        return $this;
    }

    /**
     * Get nomDirigeant
     *
     * @return string
     */
    public function getNomDirigeant()
    {
        return $this->nomDirigeant;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Contribuables
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set dateArriveeDGE
     *
     * @param \DateTime $dateArriveeDGE
     *
     * @return Contribuables
     */
    public function setDateArriveeDGE($dateArriveeDGE)
    {
        $this->dateArriveeDGE = $dateArriveeDGE;

        return $this;
    }

    /**
     * Get dateArriveeDGE
     *
     * @return \DateTime
     */
    public function getDateArriveeDGE()
    {
        return $this->dateArriveeDGE;
    }

    /**
     * Set exerciceFiscalDebut
     *
     * @param integer $exerciceFiscalDebut
     *
     * @return Contribuables
     */
    public function setExerciceFiscalDebut($exerciceFiscalDebut)
    {
        $this->exerciceFiscalDebut = $exerciceFiscalDebut;

        return $this;
    }

    /**
     * Get exerciceFiscalDebut
     *
     * @return int
     */
    public function getExerciceFiscalDebut()
    {
        return $this->exerciceFiscalDebut;
    }

    /**
     * Set exerciceFiscalFin
     *
     * @param integer $exerciceFiscalFin
     *
     * @return Contribuables
     */
    public function setExerciceFiscalFin($exerciceFiscalFin)
    {
        $this->exerciceFiscalFin = $exerciceFiscalFin;

        return $this;
    }

    /**
     * Get exerciceFiscalFin
     *
     * @return int
     */
    public function getExerciceFiscalFin()
    {
        return $this->exerciceFiscalFin;
    }

    /**
     * Set gestionnaire
     *
     * @param string $gestionnaire
     *
     * @return Contribuables
     */
    public function setGestionnaire($gestionnaire)
    {
        $this->gestionnaire = $gestionnaire;

        return $this;
    }

    /**
     * Get gestionnaire
     *
     * @return string
     */
    public function getGestionnaire()
    {
        return $this->gestionnaire;
    }

    /**
     * Set inactifDate
     *
     * @param date $inactifDate
     *
     * @return Contribuables
     */
    public function setInactifDate($inactifDate)
    {
        $this->inactifDate = $inactifDate;

        return $this;
    }

    /**
     * Get inactifDate
     *
     * @return date
     */
    public function getInactifDate()
    {
        return $this->inactifDate;
    }

}

