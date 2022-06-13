<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TacheObservation
 *
 * @ORM\Table(name="TacheObservation")
 * @ORM\Entity(repositoryClass="DBundle\Repository\TacheObservationRepository")
 */
class TacheObservation
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
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity="Tache")
     * @ORM\JoinColumn(nullable=false)
     */
    private $courrier;


    /**
     * @var string
     *
     *  @ORM\Column(name="status", type="string")
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
     * @return TacheObservation
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
     * @return TacheObservation
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
     * @param \DBundle\Entity\Tache $courrier
     *
     * @return TacheObservation
     */
    public function setCourrier(\DBundle\Entity\Tache $courrier)
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
     * Set status
     *
     * @param string $status
     *
     * @return EntrantObservation
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
