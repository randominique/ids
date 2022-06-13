<?php

namespace SIGTASBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * tax_type
 *
 * @ORM\Table(name="SIGTASAD.TAX_TYPE")
 * @ORM\Entity(repositoryClass="SIGTASBundle\Repository\tax_typeRepository")
 */
class TaxType
{
    /**
     * @var int
     *
     * @ORM\Column(name="TAX_TYPE_NO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="TAX_TYPE_DESC", type="string", length=255)
     */
    private $taxTypeDesc;

    public function __toString(){
        return ''.$this->getId();
    }

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
     * Set taxTypeDesc
     *
     * @param string $taxTypeDesc
     *
     * @return tax_type
     */
    public function setTaxTypeDesc($taxTypeDesc)
    {
        $this->taxTypeDesc = $taxTypeDesc;

        return $this;
    }

    /**
     * Get taxTypeDesc
     *
     * @return string
     */
    public function getTaxTypeDesc()
    {
        return $this->taxTypeDesc;
    }
}

