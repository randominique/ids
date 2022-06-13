<?php

namespace SQVFBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * sqvf_type_impots
 *
 * @ORM\Table(name="sqvf_type_impots")
 * @ORM\Entity(repositoryClass="SQVFBundle\Repository\sqvf_type_impotsRepository")
 */
class sqvf_type_impots
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
     * @ORM\Column(name="titre", type="string", length=50, nullable=true)
     */
    private $titre;


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
     * Set titre
     *
     * @param string $titre
     *
     * @return sqvf_type_impots
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }
}

