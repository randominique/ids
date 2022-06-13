<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AnnexeTva
 *
 * @ORM\Table(name="annexe_tva")
 * @ORM\Entity(repositoryClass="DBundle\Repository\AnnexeTvaRepository")
 */
class AnnexeTva
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
     * @ORM\Column(name="nif", type="string", length=255)
     * @Assert\NotBlank
     */
    private $nif;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_envoi", type="datetime")
     * @Assert\NotBlank
     * @Assert\DateTime
     */
    private $dateEnvoi;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_reponse", type="datetime", nullable = true)
     * @Assert\DateTime
     */
    private $dateReponse;

    /**
     * @var string
     *
     * @ORM\Column(name="observation", type="text", nullable = true)
     */
    private $observation;


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
     * @return AnnexeTva
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
     * Set dateEnvoi
     *
     * @param \DateTime $dateEnvoi
     *
     * @return AnnexeTva
     */
    public function setDateEnvoi($dateEnvoi)
    {
        
        $this->dateEnvoi = $dateEnvoi;

        return $this;
    }

    /**
     * Get dateEnvoi
     *
     * @return \DateTime
     */
    public function getDateEnvoi()
    {
        return $this->dateEnvoi;
    }

    /**
     * Set dateReponse
     *
     * @param \DateTime $dateReponse
     *
     * @return AnnexeTva
     */
    public function setDateReponse($dateReponse)
    {
        $this->dateReponse = $dateReponse;

        return $this;
    }

    /**
     * Get dateReponse
     *
     * @return \DateTime
     */
    public function getDateReponse()
    {
        return $this->dateReponse;
    }

    /**
     * Set observation
     *
     * @param string $observation
     *
     * @return AnnexeTva
     */
    public function setObservation($observation)
    {
        $this->observation = $observation;

        return $this;
    }

    /**
     * Get observation
     *
     * @return string
     */
    public function getObservation()
    {
        return $this->observation;
    }
}

