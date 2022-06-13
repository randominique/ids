<?php

namespace SIGTASBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Titre_perception
 *
 * @ORM\Table(name="SIGTASAD.TITRE_PERCEPTION")
 * @ORM\Entity(repositoryClass="SIGTASBundle\Repository\Titre_perceptionRepository")
 */
class Titre_perception
{
    /**
     * @var string
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="NIF", type="string", length=20)
     */
    private $nif;

    /**
     * @var int
     *
     * @ORM\Column(name="TAX_CENTRE_NO", type="integer", nullable=true)
     */
    private $taxCentreNo;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM", type="string", length=120)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="EXT_DOC_NO", type="string", length=15, nullable=true)
     */
    private $extDocNo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="DT", type="datetime", nullable=true)
     */
    private $dt;

    /**
     * @var string
     *
     * @ORM\Column(name="TAX_TYPE_DESC_F", type="string", length=20, nullable=true)
     */
    private $taxTypeDescF;

    /**
     * @var int
     *
     * @ORM\Column(name="PP_CHRG", type="integer", nullable=true)
     */
    private $ppChrg;

    /**
     * @var int
     *
     * @ORM\Column(name="PEN_CHRG", type="integer", nullable=true)
     */
    private $penChrg;

    /**
     * @var int
     *
     * @ORM\Column(name="INT_CHRG", type="integer", nullable=true)
     */
    private $intChrg;

    /**
     * @var int
     *
     * @ORM\Column(name="PP_PMT", type="integer", nullable=true)
     */
    private $ppPmt;

    /**
     * @var int
     *
     * @ORM\Column(name="PEN_PMT", type="integer", nullable=true)
     */
    private $penPmt;

    /**
     * @var int
     *
     * @ORM\Column(name="INT_PMT", type="integer", nullable=true)
     */
    private $intPmt;

    /**
     * @var int
     *
     * @ORM\Column(name="RAR", type="integer", nullable=true)
     */
    private $rar;

    /**
     * @var int
     *
     * @ORM\Column(name="MOIS", type="integer", nullable=true)
     */
    private $mois;

    /**
     * @var int
     *
     * @ORM\Column(name="ANNEE", type="integer", nullable=true)
     */
    private $annee;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="NOTIF_DATE", type="datetime", nullable=true)
     */
    private $notifDate;

    /**
     * @var string
     *
     * @ORM\Column(name="TITRE_ORIGINE", type="string", length=100, nullable=true)
     */
    private $titreOrigine;

    /**
     * @var int
     *
     * @ORM\Column(name="TITRE_NO", type="integer", nullable=true)
     */
    private $titreNo;

    /**
     * @var string
     *
     * @ORM\Column(name="PERSONAL_TEXT", type="text", nullable=true)
     */
    private $personalText;


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
     * @return Titre_perception
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
     * Set taxCentreNo
     *
     * @param integer $taxCentreNo
     *
     * @return Titre_perception
     */
    public function setTaxCentreNo($taxCentreNo)
    {
        $this->taxCentreNo = $taxCentreNo;

        return $this;
    }

    /**
     * Get taxCentreNo
     *
     * @return int
     */
    public function getTaxCentreNo()
    {
        return $this->taxCentreNo;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return Titre_perception
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
     * Set extDocNo
     *
     * @param string $extDocNo
     *
     * @return Titre_perception
     */
    public function setExtDocNo($extDocNo)
    {
        $this->extDocNo = $extDocNo;

        return $this;
    }

    /**
     * Get extDocNo
     *
     * @return string
     */
    public function getExtDocNo()
    {
        return $this->extDocNo;
    }

    /**
     * Set dt
     *
     * @param \DateTime $dt
     *
     * @return Titre_perception
     */
    public function setDt($dt)
    {
        $this->dt = $dt;

        return $this;
    }

    /**
     * Get dt
     *
     * @return \DateTime
     */
    public function getDt()
    {
        return $this->dt;
    }

    /**
     * Set taxTypeDescF
     *
     * @param string $taxTypeDescF
     *
     * @return Titre_perception
     */
    public function setTaxTypeDescF($taxTypeDescF)
    {
        $this->taxTypeDescF = $taxTypeDescF;

        return $this;
    }

    /**
     * Get taxTypeDescF
     *
     * @return string
     */
    public function getTaxTypeDescF()
    {
        return $this->taxTypeDescF;
    }

    /**
     * Set ppChrg
     *
     * @param integer $ppChrg
     *
     * @return Titre_perception
     */
    public function setPpChrg($ppChrg)
    {
        $this->ppChrg = $ppChrg;

        return $this;
    }

    /**
     * Get ppChrg
     *
     * @return int
     */
    public function getPpChrg()
    {
        return $this->ppChrg;
    }

    /**
     * Set penChrg
     *
     * @param integer $penChrg
     *
     * @return Titre_perception
     */
    public function setPenChrg($penChrg)
    {
        $this->penChrg = $penChrg;

        return $this;
    }

    /**
     * Get penChrg
     *
     * @return int
     */
    public function getPenChrg()
    {
        return $this->penChrg;
    }

    /**
     * Set intChrg
     *
     * @param integer $intChrg
     *
     * @return Titre_perception
     */
    public function setIntChrg($intChrg)
    {
        $this->intChrg = $intChrg;

        return $this;
    }

    /**
     * Get intChrg
     *
     * @return int
     */
    public function getIntChrg()
    {
        return $this->intChrg;
    }

    /**
     * Set ppPmt
     *
     * @param integer $ppPmt
     *
     * @return Titre_perception
     */
    public function setPpPmt($ppPmt)
    {
        $this->ppPmt = $ppPmt;

        return $this;
    }

    /**
     * Get ppPmt
     *
     * @return int
     */
    public function getPpPmt()
    {
        return $this->ppPmt;
    }

    /**
     * Set penPmt
     *
     * @param integer $penPmt
     *
     * @return Titre_perception
     */
    public function setPenPmt($penPmt)
    {
        $this->penPmt = $penPmt;

        return $this;
    }

    /**
     * Get penPmt
     *
     * @return int
     */
    public function getPenPmt()
    {
        return $this->penPmt;
    }

    /**
     * Set intPmt
     *
     * @param integer $intPmt
     *
     * @return Titre_perception
     */
    public function setIntPmt($intPmt)
    {
        $this->intPmt = $intPmt;

        return $this;
    }

    /**
     * Get intPmt
     *
     * @return int
     */
    public function getIntPmt()
    {
        return $this->intPmt;
    }

    /**
     * Set rar
     *
     * @param integer $rar
     *
     * @return Titre_perception
     */
    public function setRar($rar)
    {
        $this->rar = $rar;

        return $this;
    }

    /**
     * Get rar
     *
     * @return int
     */
    public function getRar()
    {
        return $this->rar;
    }

    /**
     * Set mois
     *
     * @param integer $mois
     *
     * @return Titre_perception
     */
    public function setMois($mois)
    {
        $this->mois = $mois;

        return $this;
    }

    /**
     * Get mois
     *
     * @return int
     */
    public function getMois()
    {
        return $this->mois;
    }

    /**
     * Set annee
     *
     * @param integer $annee
     *
     * @return Titre_perception
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
     * Set notifDate
     *
     * @param \DateTime $notifDate
     *
     * @return Titre_perception
     */
    public function setNotifDate($notifDate)
    {
        $this->notifDate = $notifDate;

        return $this;
    }

    /**
     * Get notifDate
     *
     * @return \DateTime
     */
    public function getNotifDate()
    {
        return $this->notifDate;
    }

    /**
     * Set titreOrigine
     *
     * @param string $titreOrigine
     *
     * @return Titre_perception
     */
    public function setTitreOrigine($titreOrigine)
    {
        $this->titreOrigine = $titreOrigine;

        return $this;
    }

    /**
     * Get titreOrigine
     *
     * @return string
     */
    public function getTitreOrigine()
    {
        return $this->titreOrigine;
    }

    /**
     * Set titreNo
     *
     * @param integer $titreNo
     *
     * @return Titre_perception
     */
    public function setTitreNo($titreNo)
    {
        $this->titreNo = $titreNo;

        return $this;
    }

    /**
     * Get titreNo
     *
     * @return int
     */
    public function getTitreNo()
    {
        return $this->titreNo;
    }

    /**
     * Set personalText
     *
     * @param string $personalText
     *
     * @return Titre_perception
     */
    public function setPersonalText($personalText)
    {
        $this->personalText = $personalText;

        return $this;
    }

    /**
     * Get personalText
     *
     * @return string
     */
    public function getPersonalText()
    {
        return $this->personalText;
    }
}

