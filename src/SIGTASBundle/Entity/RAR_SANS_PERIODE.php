<?php

namespace SIGTASBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RAR_SANS_PERIODE
 *
 * @ORM\Table(name="SIGTASAD.RAR_SANS_PERIODE")
 * @ORM\Entity(repositoryClass="SIGTASBundle\Repository\RAR_SANS_PERIODERepository")
 */
class RAR_SANS_PERIODE
{
    /**
     * @var string
     *
     * @ORM\Column(name="NIF", type="integer", length=20, nullable=true)
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
     * @ORM\Column(name="RS", type="string", length=120, nullable=true)
     */
    private $rs;

    /**
     * @var int
     *
     * @ORM\Column(name="TAX_PERIOD_NO", type="integer", nullable=true)
     */
    private $taxPeriodNo;

    /**
     * @var int
     *
     * @ORM\Column(name="ANNEE", type="integer", nullable=true)
     */
    private $annee;

    /**
     * @var int
     *
     * @ORM\Column(name="MOIS", type="integer", nullable=true)
     */
    private $mois;

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
     * @param int $nif
     *
     * @return RAR_SANS_PERIODE
     */
    public function setNif($nif)
    {
        $this->nif = $nif;

        return $this;
    }

    /**
     * Get nif
     *
     * @return int
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
     * @return RAR_SANS_PERIODE
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
     * Set rs
     *
     * @param string $rs
     *
     * @return RAR_SANS_PERIODE
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
     * Set taxPeriodNo
     *
     * @param integer $taxPeriodNo
     *
     * @return RAR_SANS_PERIODE
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
     * Set annee
     *
     * @param integer $annee
     *
     * @return RAR_SANS_PERIODE
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
     * Set mois
     *
     * @param integer $mois
     *
     * @return RAR_SANS_PERIODE
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
     * Set nature
     *
     * @param string $nature
     *
     * @return RAR_SANS_PERIODE
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
     * @param string $taxTypeNo
     *
     * @return RAR_SANS_PERIODE
     */
    public function setTaxTypeNo($taxTypeNo)
    {
        $this->taxTypeNo = $taxTypeNo;

        return $this;
    }

    /**
     * Get taxTypeNo
     *
     * @return string
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
     * @return RAR_SANS_PERIODE
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
     * @return RAR_SANS_PERIODE
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
