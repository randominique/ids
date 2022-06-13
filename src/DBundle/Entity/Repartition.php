<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Repartition
 *
 * @ORM\Table(name="Repartition")
 * @ORM\Entity(repositoryClass="DBundle\Repository\RepartitionRepository")
 */
class Repartition
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
     * @ORM\Column(name="nif", type="string", length=255, nullable=true)
     */
    private $nif;

    /**
     * @var string
     *
     * @ORM\Column(name="rs", type="string", length=255, nullable=true)
     */
    private $rs;

    /**
     * @var int
     *
     * @ORM\Column(name="gestionnaireId", type="integer", nullable=true)
     */
    private $gestionnaireId;


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
     * @return Repartition
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
     * @return Repartition
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
     * Set gestionnaireId
     *
     * @param integer $gestionnaireId
     *
     * @return Repartition
     */
    public function setGestionnaireId($gestionnaireId)
    {
        $this->gestionnaireId = $gestionnaireId;

        return $this;
    }

    /**
     * Get gestionnaireId
     *
     * @return int
     */
    public function getGestionnaireId()
    {
        return $this->gestionnaireId;
    }
}

