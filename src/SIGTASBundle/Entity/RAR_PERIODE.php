<?php

namespace SIGTASBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RAR_PERIODE
 *
 * @ORM\Table(name="SIGTASAD.RAR_PERIODE")
 * @ORM\Entity(repositoryClass="SIGTASBundle\Repository\RAR_PERIODERepository")
 */
class RAR_PERIODE
{
    /**
     * @var string
     *
     * @ORM\Column(name="FISCAL_NO", type="string", length=20, nullable=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $nif;

    /**
     * @var string
     *
     * @ORM\Column(name="TAX_CENTRE_CODE", type="string", length=8, nullable=true)
     */
    private $taxCentreCode;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM", type="string", length=120, nullable=true)
     */
    private $nom;

    /**
     * @var int
     *
     * @ORM\Column(name="TAX_PERIOD_NO", type="integer", nullable=true)
     */
    private $taxPeriodNo;

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
     * @ORM\Column(name="NATURE", type="string", length=20, nullable=true)
     */
    private $nature;

    /**
     * @var int
     *
     * @ORM\Column(name="TAX_TYPE_NO", type="integer", nullable=true)
     */
    private $taxTypeNo;

    /**
     * @var string
     *
     * @ORM\Column(name="TYPE", type="string", length=9, nullable=true)
     */
    private $type;

    /**
     * @var int
     *
     * @ORM\Column(name="MONTANT", type="integer", nullable=true)
     */
    private $montant;


    /**
     * Set nif
     *
     * @param string $nif
     *
     * @return RAR_PERIODE
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
     * Set taxCentreCode
     *
     * @param string $taxCentreCode
     *
     * @return RAR_PERIODE
     */
    public function setTaxCentreCode($taxCentreCode)
    {
        $this->taxCentreCode = $taxCentreCode;

        return $this;
    }

    /**
     * Get taxCentreCode
     *
     * @return string
     */
    public function getTaxCentreCode()
    {
        return $this->taxCentreCode;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return RAR_PERIODE
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
     * Set taxPeriodNo
     *
     * @param integer $taxPeriodNo
     *
     * @return RAR_PERIODE
     */
    public function setTaxPeriodNo($taxPeriodNo)
    {
        $this->taxPeriodNo = $taxPeriodNo;

        return $this;
    }

    /**
     * Get taxPeriodNo
     *
     * @return int
     */
    public function getTaxPeriodNo()
    {
        return $this->taxPeriodNo;
    }

    /**
     * Set tperYear
     *
     * @param integer $tperYear
     *
     * @return RAR_PERIODE
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
     * @return RAR_PERIODE
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
     * Set nature
     *
     * @param string $nature
     *
     * @return RAR_PERIODE
     */
    public function setNature($nature)
    {
        $this->nature = $nature;

        return $this;
    }

    /**
     * Get nature
     *
     * @return string
     */
    public function getNature()
    {
        return $this->nature;
    }

    /**
     * Set taxTypeNo
     *
     * @param integer $taxTypeNo
     *
     * @return RAR_PERIODE
     */
    public function setTaxTypeNo($taxTypeNo)
    {
        $this->taxTypeNo = $taxTypeNo;

        return $this;
    }

    /**
     * Get taxTypeNo
     *
     * @return int
     */
    public function getTaxTypeNo()
    {
        return $this->taxTypeNo;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return RAR_PERIODE
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
     * Set montant
     *
     * @param integer $montant
     *
     * @return RAR_PERIODE
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return int
     */
    public function getMontant()
    {
        return $this->montant;
    }
}