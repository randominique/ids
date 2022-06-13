<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * tax_famille
 *
 * @ORM\Table(name="tax_famille")
 * @ORM\Entity(repositoryClass="DBundle\Repository\tax_familleRepository")
 */
class tax_famille
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;
    
    /**
     * @ORM\OneToMany(targetEntity="tax_famille_child", mappedBy="famille")
     */
    private $membre;

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
     * Set name
     *
     * @param string $name
     *
     * @return tax_famille
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->membre = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add membre
     *
     * @param \DBundle\Entity\tax_famille_child $membre
     *
     * @return tax_famille
     */
    public function addMembre(\DBundle\Entity\tax_famille_child $membre)
    {
        $this->membre[] = $membre;

        return $this;
    }

    /**
     * Remove membre
     *
     * @param \DBundle\Entity\tax_famille_child $membre
     */
    public function removeMembre(\DBundle\Entity\tax_famille_child $membre)
    {
        $this->membre->removeElement($membre);
    }

    /**
     * Get membre
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMembre()
    {
        return $this->membre;
    }
}
