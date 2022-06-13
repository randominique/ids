<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SuiviDossiers
 *
 * @ORM\Table(name="suivi_dossiers")
 * @ORM\Entity(repositoryClass="DBundle\Repository\SuiviDossiersRepository")
 */
class SuiviDossiers
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
     */
    private $nif;
       
    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="dossiers_enlever")
     * @ORM\JoinColumn(nullable=false)
     */
    private $agent;
    
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $responsable_sortie;
    
    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $responsable_remise;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_enlevement", type="datetime")
     */
    private $dateEnlevement;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_remise", type="datetime", nullable=true)
     */
    private $dateRemise;

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
     * @return SuiviDossiers
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
     * Set dateEnlevement
     *
     * @param \DateTime $dateEnlevement
     *
     * @return SuiviDossiers
     */
    public function setDateEnlevement($dateEnlevement)
    {
        $this->dateEnlevement = $dateEnlevement;

        return $this;
    }

    /**
     * Get dateEnlevement
     *
     * @return \DateTime
     */
    public function getDateEnlevement()
    {
        return $this->dateEnlevement;
    }

    /**
     * Set dateRemise
     *
     * @param \DateTime $dateRemise
     *
     * @return SuiviDossiers
     */
    public function setDateRemise($dateRemise)
    {
        $this->dateRemise = $dateRemise;

        return $this;
    }

    /**
     * Get dateRemise
     *
     * @return \DateTime
     */
    public function getDateRemise()
    {
        return $this->dateRemise;
    }

    /**
     * Set observation
     *
     * @param string $observation
     *
     * @return SuiviDossiers
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

    /**
     * Set agent
     *
     * @param \DBundle\Entity\User $agent
     *
     * @return SuiviDossiers
     */
    public function setAgent(\DBundle\Entity\User $agent)
    {
        $this->agent = $agent;

        return $this;
    }

    /**
     * Get agent
     *
     * @return \DBundle\Entity\User
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * Set responsableSortie
     *
     * @param \DBundle\Entity\User $responsableSortie
     *
     * @return SuiviDossiers
     */
    public function setResponsableSortie(\DBundle\Entity\User $responsableSortie)
    {
        $this->responsable_sortie = $responsableSortie;

        return $this;
    }

    /**
     * Get responsableSortie
     *
     * @return \DBundle\Entity\User
     */
    public function getResponsableSortie()
    {
        return $this->responsable_sortie;
    }

    /**
     * Set responsableRemise
     *
     * @param \DBundle\Entity\User $responsableRemise
     *
     * @return SuiviDossiers
     */
    public function setResponsableRemise(\DBundle\Entity\User $responsableRemise)
    {
        $this->responsable_remise = $responsableRemise;

        return $this;
    }

    /**
     * Get responsableRemise
     *
     * @return \DBundle\Entity\User
     */
    public function getResponsableRemise()
    {
        return $this->responsable_remise;
    }
}
