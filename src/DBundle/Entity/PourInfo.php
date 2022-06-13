<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PourInfo
 *
 * @ORM\Table(name="pour_info")
 * @ORM\Entity(repositoryClass="DBundle\Repository\PourInfoRepository")
 */
class PourInfo
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
     * @ORM\ManyToOne(targetEntity="DocCourrier",inversedBy ="pourInfo")
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
     * @param \SIGTASBundle\Entity\DocCourrier $courrier
     *
     * @return PourInfo
     */
    public function setCourrier(\SIGTASBundle\Entity\DocCourrier $courrier = null)
    {
        $this->courrier = $courrier;

        return $this;
    }

    /**
     * Get courrier
     *
     * @return \SIGTASBundle\Entity\DocCourrier
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
