<?php

namespace SIGTASBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CarteFiscale
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="SIGTASAD.CARTE_STAT")
 * @ORM\Entity(repositoryClass="SIGTASBundle\Repository\CarteFiscaleRepository")
 */
class CarteFiscale
{

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="FISCAL_NO", type="string", length=255)
     */
    private $nif;

    /**
     * @var string
     *
     * @ORM\Column(name="ANARANA", type="string", length=255, nullable=true)
     */
    private $anarana;

    /**
     * @var string
     *
     * @ORM\Column(name="LIEU", type="string", length=255, nullable=true)
     */
    private $lieu;

    /**
     * @var string
     *
     * @ORM\Column(name="ADDRESS", type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="CARTE_NUM", type="string", length=255, nullable=true)
     */
    private $carteNum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CARTE_DATE",  type="oracledate", nullable=true)
     */
    private $carteDate;

    /**
     * @var string
     *
     * @ORM\Column(name="CARTE_TYPE_NO", type="string", length=10, nullable=true)
     */
    private $carteTypeNo;

    /**
     * @var string
     *
     * @ORM\Column(name="NUM_VEHICULE", type="string", length=255, nullable=true)
     */
    private $numVehicule;

    /**
     * @var int
     *
     * @ORM\Column(name="ANNEE", type="integer", nullable=true)
     */
    private $annee;

    /**
     * @var int
     *
     * @ORM\Column(name="CARTE_MOTIF_NO", type="integer", nullable=true)
     */
    private $carteMotifNo;

    /**
     * @var string
     *
     * @ORM\Column(name="TYPE", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="CARTE_DESC", type="string", length=255, nullable=true)
     */
    private $carteDesc;


    /**
     * Set nif
     *
     * @param string $nif
     *
     * @return CarteFiscale
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
     * Set anarana
     *
     * @param string $anarana
     *
     * @return CarteFiscale
     */
    public function setAnarana($anarana)
    {
        $this->anarana = $anarana;

        return $this;
    }

    /**
     * Get anarana
     *
     * @return string
     */
    public function getAnarana()
    {
        return $this->anarana;
    }

    /**
     * Set lieu
     *
     * @param string $lieu
     *
     * @return CarteFiscale
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;

        return $this;
    }

    /**
     * Get lieu
     *
     * @return string
     */
    public function getLieu()
    {
        return $this->lieu;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return CarteFiscale
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
     * Set carteNum
     *
     * @param string $carteNum
     *
     * @return CarteFiscale
     */
    public function setCarteNum($carteNum)
    {
        $this->carteNum = $carteNum;

        return $this;
    }

    /**
     * Get carteNum
     *
     * @return string
     */
    public function getCarteNum()
    {
        return $this->carteNum;
    }

    /**
     * Set carteDate
     *
     * @param \DateTime $carteDate
     *
     * @return CarteFiscale
     */
    public function setCarteDate($carteDate)
    {
        $this->carteDate = $carteDate;

        return $this;
    }

    /**
     * Get carteDate
     *
     * @return \DateTime
     */
    public function getCarteDate()
    {
        return $this->carteDate;
    }

    /**
     * Set carteTypeNo
     *
     * @param string $carteTypeNo
     *
     * @return CarteFiscale
     */
    public function setCarteTypeNo($carteTypeNo)
    {
        $this->carteTypeNo = $carteTypeNo;

        return $this;
    }

    /**
     * Get carteTypeNo
     *
     * @return string
     */
    public function getCarteTypeNo()
    {
        return $this->carteTypeNo;
    }

    /**
     * Set numVehicule
     *
     * @param string $numVehicule
     *
     * @return CarteFiscale
     */
    public function setNumVehicule($numVehicule)
    {
        $this->numVehicule = $numVehicule;

        return $this;
    }

    /**
     * Get numVehicule
     *
     * @return string
     */
    public function getNumVehicule()
    {
        return $this->numVehicule;
    }

    /**
     * Set annee
     *
     * @param integer $annee
     *
     * @return CarteFiscale
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return int
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set carteMotifNo
     *
     * @param integer $carteMotifNo
     *
     * @return CarteFiscale
     */
    public function setCarteMotifNo($carteMotifNo)
    {
        $this->carteMotifNo = $carteMotifNo;

        return $this;
    }

    /**
     * Get carteMotifNo
     *
     * @return int
     */
    public function getCarteMotifNo()
    {
        return $this->carteMotifNo;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return CarteFiscale
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set carteDesc
     *
     * @param string $carteDesc
     *
     * @return CarteFiscale
     */
    public function setCarteDesc($carteDesc)
    {
        $this->carteDesc = $carteDesc;

        return $this;
    }

    /**
     * Get carteDesc
     *
     * @return string
     */
    public function getCarteDesc()
    {
        return $this->carteDesc;
    }
}

