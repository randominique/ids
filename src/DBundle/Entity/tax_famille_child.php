<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * tax_famille_child
 *
 * @ORM\Table(name="tax_famille_child")
 * @ORM\Entity(repositoryClass="DBundle\Repository\tax_famille_childRepository")
 */
class tax_famille_child
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
     * @ORM\Column(name="tax_type_no", type="integer")
     */
    private $taxTypeNo;

    /**
     * @ORM\ManyToOne(targetEntity="tax_famille", inversedBy="membre")
     * @ORM\JoinColumn(nullable=false)
     */
    private $famille;
    
    private $taxeName;

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
     * Set taxTypeNo
     *
     * @param integer $taxTypeNo
     *
     * @return tax_famille_child
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
     * Set famille
     *
     * @param \DBundle\Entity\tax_famille $famille
     *
     * @return tax_famille_child
     */
    public function setFamille(\DBundle\Entity\tax_famille $famille)
    {
        $this->famille = $famille;

        return $this;
    }

    /**
     * Get famille
     *
     * @return \DBundle\Entity\tax_famille
     */
    public function getFamille()
    {
        return $this->famille;
    }

    
    /**
     * Set taxeName
     *
     * @param string $taxeName
     *
     * @return Clients
     */
    public function setTaxeName($taxeName)
    {
        $this->taxeName = $taxeName;

        return $this;
    }

    /**
     * Get taxeName
     *
     * @return string
     */
    public function getTaxeName()
    {
        return $this->taxeName;
    }
}
