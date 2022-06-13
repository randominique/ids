<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SaiSetting
 *
 * @ORM\Table(name="sai_setting")
 * @ORM\Entity(repositoryClass="DBundle\Repository\SaiSettingRepository")
 */
class SaiSetting
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
     * @ORM\OneToOne(targetEntity="DBundle\Entity\Service", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true)
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
     * Set service
     *
     * @param \DBundle\Entity\Service $service
     *
     * @return SaiSetting
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
