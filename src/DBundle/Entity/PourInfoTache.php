<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PourInfo
 *
 * @ORM\Table(name="pour_info_tache")
 * @ORM\Entity(repositoryClass="DBundle\Repository\PourInfoTacheRepository")
 */
class PourInfoTache
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
     * @ORM\ManyToOne(targetEntity="Tache")
     */
    private $courrier;

    /**
     * @ORM\ManyToOne(targetEntity="Service")
     */
    private $service;



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
     * Set courrier
     *
     * @param \DBundle\Entity\Tache $courrier
     *
     * @return PourInfo
     */
    public function setCourrier(\DBundle\Entity\Tache $courrier = null)
    {
        $this->courrier = $courrier;

        return $this;
    }

    /**
     * Get courrier
     *
     * @return \DBundle\Entity\Tache
     */
    public function getCourrier()
    {
        return $this->courrier;
    }

    /**
     * Set service
     *
     * @param \DBundle\Entity\Service $service
     *
     * @return PourInfo
     */
    public function setService(\DBundle\Entity\Service $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \DBundle\Entity\Service
     */
    public function getService()
    {
        return $this->service;
    }
}
