<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeDeDossier
 *
 * @ORM\Table(name="type_de_dossier")
 * @ORM\Entity(repositoryClass="DBundle\Repository\TypeDeDossierRepository")
 */
class TypeDeDossier
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
     * @ORM\Column(name="nature", type="string", length=255, nullable=true)
     */
    private $nature;


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
     * Set nature
     *
     * @param string $nature
     *
     * @return TypeDeDossier
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
}

