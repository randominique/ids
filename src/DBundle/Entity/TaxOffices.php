<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TaxOffices
 *
 * @ORM\Table(name="TaxOffices")
 * @ORM\Entity(repositoryClass="DBundle\Repository\TaxOfficesRepository")
 */
class TaxOffices
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
     * @ORM\Column(name="FISCAL_NO", type="string", length=20)
     */
    private $nif;

    /**
     * @var string
     *
     * @ORM\Column(name="RAISON_SOCIALE", type="string", length=255)
     */
    private $rs;

    /**
     * @var int
     *
     * @ORM\Column(name="TAX_CENTRE_NO", type="integer", nullable=true)
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
     * @ORM\Column(name="TAX_TYPE_DESC_F", type="string", length=255)
     */
    private $taxTypeDescF;

    /**
     * @var int
     *
     * @ORM\Column(name="TPER_YEAR", type="integer", nullable=true)
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
     *
     * @ORM\Column(name="EXT_DOC_NO", type="string", length=15, nullable=true)
     */
    private $extDocNo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ENTRY_DATE", type="datetime", nullable=true)
     */
    private $entryDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="CREATED_DATE", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(name="TAXE", type="integer", nullable=true)
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
     * @return TaxOffices
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
     * @return TaxOffices
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
     * Set taxTypeDescF
     *
     * @param string $taxTypeDescF
     *
     * @return TaxOffices
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
     * @return TaxOffices
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
     * @return TaxOffices
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
     * Set taxe
     *
     * @param integer $taxe
     *
     * @return TaxOffices
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
     * @return TaxOffices
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
     * @return TaxOffices
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

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return TaxOffices
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
     * Set entryDate
     *
     * @param \DateTime $entryDate
     *
     * @return TaxOffices
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
     * Set taxCentreNo
     *
     * @param integer $taxCentreNo
     *
     * @return TaxOffices
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
     * @return TaxOffices
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
     * @return TaxOffices
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
}

