<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * MissionObservation
 *
 * @ORM\Table(name="missionObservation")
 * @ORM\Entity(repositoryClass="DBundle\Repository\MissionObservationRepository")
 */
class MissionObservation
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
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="Missions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $courrier;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255)
     */
    private $status;


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
     * Set message
     *
     * @param string $message
     *
     * @return MissionObservation
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return MissionObservation
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set user
     *
     * @param \DBundle\Entity\User $user
     *
     * @return TacheObservation
     */
    public function setUser(\DBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \DBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set courrier
     *
     * @param \DBundle\Entity\Missions $courrier
     *
     * @return MissionObservation
     */
    public function setCourrier(\DBundle\Entity\Missions $courrier)
    {
        $this->courrier = $courrier;

        return $this;
    }

    /**
     * Get courrier
     *
     * @return \DBundle\Entity\Missions
     */
    public function getCourrier()
    {
        return $this->courrier;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return MissionObservation
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }
}

