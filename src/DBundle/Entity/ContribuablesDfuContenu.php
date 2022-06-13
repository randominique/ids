<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ContribuablesDfuContenu
 *
 * @ORM\Table(name="ContribuablesDfuContenu")
 * @ORM\Entity(repositoryClass="DBundle\Repository\ContribuablesDfuContenuRepository")
 */
class ContribuablesDfuContenu
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
     * @ORM\Column(name="objet_id", type="integer")
     */
    private $objetId;

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
     * @ORM\ManyToOne(targetEntity="Contribuables")
     * @ORM\JoinColumn(nullable=false)
     */
    private $contribuable;


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
     * Set objetId
     *
     * @param string $objetId
     *
     * @return ContribuablesDfuContenu
     */
    public function setObjetId($objetId)
    {
        $this->objetId = $objetId;

        return $this;
    }

    /**
     * Get objetId
     *
     * @return string
     */
    public function getObjetId()
    {
        return $this->objetId;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return ContribuablesDfuContenu
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
     * @return ContribuablesDfuContenu
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
     * Set contribuable
     *
     * @param \DBundle\Entity\Contribuables $contribuable
     *
     * @return ContribuablesDfuContenu
     */
    public function setContribuable(\DBundle\Entity\Contribuables $contribuable)
    {
        $this->contribuable = $contribuable;

        return $this;
    }

    /**
     * Get contribuable
     *
     * @return \DBundle\Entity\Contribuables
     */
    public function getContribuable()
    {
        return $this->contribuable;
    }
        /**
     * Set status
     *
     * @param string $status
     *
     * @return ContribuablesDfuContenu
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
