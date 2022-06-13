<?php

namespace SIGTASBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaxationOffice
 * @ORM\Entity(readOnly=true)
 * @ORM\Table(name="SIGTASAD.TAXATION_OFFICE")
 * @ORM\Entity(repositoryClass="SIGTASBundle\Repository\TaxationOfficeRepository")
 */
class TaxationOffice
{

    /**
     * @var string
     * 
     * @ORM\Column(name="FISCAL_NO", type="string", length=255)
     */
    private $nif;

    /**
     * @var int
     *
     * @ORM\Column(name="TAX_CENTRE_NO", type="integer")
     */
    private $taxCentreNo;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM", type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="TAX_TYPE_DESC_F", type="string", length=255, nullable=true)
     */
    private $taxTypeDescF;

    /**
     * @var int
     *
     * @ORM\Column(name="TPER_YEAR", type="integer")
     */
    private $tperYear;

    /**
     * @var int
     *
     * @ORM\Column(name="TPER_MONTH", type="integer", nullable=true)
     */
    private $tperMonth;

    /**
     * @var string
     * @ORM\Id
     * @ORM\Column(name="EXT_DOC_NO", type="string", length=255, nullable=true)
     */
    private $extDocNo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ENTRY_DATE", type="oracledate")
     */
    private $entryDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CREATED_DATE", type="oracledate")
     */
    private $createdDate;
    
    /**
     * @var int
     *
     * @ORM\Column(name="TAXE", type="integer")
     */
    private $taxe;

    /**
     * @var int
     *
     * @ORM\Column(name="PEN", type="integer", nullable=true)
     */
    private $pen;

    /**
     * @var int
     *
     * @ORM\Column(name="INTE", type="integer", nullable=true)
     */
    private $inte;

    /**
     * Set nif
     *
     * @param string $nif
     *
     * @return TaxationOffice
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
     * @return TaxationOffice
     */
    public function settaxCentreNo($taxCentreNo)
    {
        $this->taxCentreNo = $taxCentreNo;

        return $this;
    }

    /**
     * Get taxCentreNo
     *
     * @return int
     */
    public function gettaxCentreNo()
    {
        return $this->taxCentreNo;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return TaxationOffice
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
     * Set taxTypeDescF
     *
     * @param string $taxTypeDescF
     *
     * @return TaxationOffice
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
     * Set tperYear
     *
     * @param integer $tperYear
     *
     * @return TaxationOffice
     */
    public function setTperYear($tperYear)
    {
        $this->tperYear = $tperYear;

        return $this;
    }

    /**
     * Get tperYear
     *
     * @return int
     */
    public function getTperYear()
    {
        return $this->tperYear;
    }

    /**
     * Set tperMonth
     *
     * @param integer $tperMonth
     *
     * @return TaxationOffice
     */
    public function setTperMonth($tperMonth)
    {
        $this->tperMonth = $tperMonth;

        return $this;
    }

    /**
     * Get tperMonth
     *
     * @return int
     */
    public function getTperMonth()
    {
        return $this->tperMonth;
    }

    /**
     * Set extDocNo
     *
     * @param string $extDocNo
     *
     * @return TaxationOffice
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
     * Set entryDate
     *
     * @param \DateTime $entryDate
     *
     * @return TaxationOffice
     */
    public function setEntryDate($entryDate)
    {
        $this->entryDate = $entryDate;

        return $this;
    }

    /**
     * Get entryDate
     *
     * @return \DateTime
     */
    public function getEntryDate()
    {
        return $this->entryDate;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return TaxationOffice
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Set taxe
     *
     * @param integer $taxe
     *
     * @return TaxationOffice
     */
    public function setTaxe($taxe)
    {
        $this->taxe = $taxe;

        return $this;
    }

    /**
     * Get taxe
     *
     * @return int
     */
    public function getTaxe()
    {
        return $this->taxe;
    }

    /**
     * Set pen
     *
     * @param integer $pen
     *
     * @return TaxationOffice
     */
    public function setPen($pen)
    {
        $this->pen = $pen;

        return $this;
    }

    /**
     * Get pen
     *
     * @return int
     */
    public function getPen()
    {
        return $this->pen;
    }

    /**
     * Set inte
     *
     * @param integer $inte
     *
     * @return TaxationOffice
     */
    public function setInte($inte)
    {
        $this->inte = $inte;

        return $this;
    }

    /**
     * Get inte
     *
     * @return int
     */
    public function getInte()
    {
        return $this->inte;
    }
}

