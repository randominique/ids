<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Communication
 *
 * @ORM\Table(name="communication")
 * @ORM\Entity(repositoryClass="DBundle\Repository\CommunicationRepository")
 */
class Communication
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
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="date")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="typecommunication", type="string", length=100)
     */
    private $typecommunication;

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
     * @var string
     *
     * @ORM\Column(name="contact", type="string", length=255, nullable=true)
     */
    private $contact;

    /**
     * @var string
     *
     * @ORM\Column(name="interlocuteur", type="string", length=255, nullable=true)
     */
    private $interlocuteur;

    /**
     * @var string
     *
     * @ORM\Column(name="objet", type="text", nullable=true)
     */
    private $objet;

    /**
     * @var string
     *
     * @ORM\Column(name="resolutions", type="text", nullable=true)
     */
    private $resolutions;

    /**
     * @var string
     *
     * @ORM\Column(name="createdByUser", type="string", length=255, nullable=true)
     */
    private $createdByUser;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="updatedUser", type="string", length=255, nullable=true)
     */
    private $updatedUser;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

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
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Communication
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
     * Set typecommunication
     *
     * @param string $typecommunication
     *
     * @return Communication
     */
    public function setTypecommunication($typecommunication)
    {
        $this->typecommunication = $typecommunication;

        return $this;
    }

    /**
     * Get typecommunication
     *
     * @return string
     */
    public function getTypecommunication()
    {
        return $this->typecommunication;
    }

    /**
     * Set nif
     *
     * @param string $nif
     *
     * @return Communication
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
     * @return Communication
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
     * Set contact
     *
     * @param string $contact
     *
     * @return Communication
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return string
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set interlocuteur
     *
     * @param string $interlocuteur
     *
     * @return Communication
     */
    public function setInterlocuteur($interlocuteur)
    {
        $this->interlocuteur = $interlocuteur;

        return $this;
    }

    /**
     * Get interlocuteur
     *
     * @return string
     */
    public function getInterlocuteur()
    {
        return $this->interlocuteur;
    }

    /**
     * Set objet
     *
     * @param string $objet
     *
     * @return Communication
     */
    public function setObjet($objet)
    {
        $this->objet = $objet;

        return $this;
    }

    /**
     * Get objet
     *
     * @return string
     */
    public function getObjet()
    {
        return $this->objet;
    }

    /**
     * Set resolutions
     *
     * @param string $resolutions
     *
     * @return Communication
     */
    public function setResolutions($resolutions)
    {
        $this->resolutions = $resolutions;

        return $this;
    }

    /**
     * Get resolutions
     *
     * @return string
     */
    public function getResolutions()
    {
        return $this->resolutions;
    }

    /**
     * Set createdByUser
     *
     * @param string $createdByUser
     *
     * @return Communication
     */
    public function setCreatedByUser($createdByUser)
    {
        $this->createdByUser = $createdByUser;

        return $this;
    }

    /**
     * Get createdByUser
     *
     * @return string
     */
    public function getCreatedByUser()
    {
        return $this->createdByUser;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Communication
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set updatedUser
     *
     * @param string $updatedUser
     *
     * @return Communication
     */
    public function setUpdatedUser($updatedUser)
    {
        $this->updatedUser = $updatedUser;

        return $this;
    }

    /**
     * Get updatedUser
     *
     * @return string
     */
    public function getUpdatedUser()
    {
        return $this->updatedUser;
    }
}
