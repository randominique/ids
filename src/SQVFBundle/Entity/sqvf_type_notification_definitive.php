<?php

namespace SQVFBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeNotifD
 *
 * @ORM\Table(name="sqvf_type_notification_definitive")
 * @ORM\Entity(repositoryClass="SQVFBundle\Repository\sqvf_type_notification_definitiveRepository")
 */
class sqvf_type_notification_definitive
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
     * @var int
     *
     * @ORM\Column(name="archive", type="integer", nullable=true)
     */
    private $archive;


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
     * @return TypeNotifD
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

    /**
     * Set archive
     *
     * @param integer $archive
     *
     * @return TypeNotifD
     */
    public function setArchive($archive)
    {
        $this->archive = $archive;

        return $this;
    }

    /**
     * Get archive
     *
     * @return int
     */
    public function getArchive()
    {
        return $this->archive;
    }
}

