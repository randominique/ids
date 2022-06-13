<?php

namespace SQVFBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeNotifR
 *
 * @ORM\Table(name="sqvf_type_notification_redressement")
 * @ORM\Entity(repositoryClass="SQVFBundle\Repository\sqvf_type_notification_redressementRepository")
 */
class sqvf_type_notification_redressement
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
     * @ORM\Column(name="titre", type="text", nullable=true)
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
     * @return TypeNotifR
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

