<?php

namespace SQVFBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AgentVerificateur
 *
 * @ORM\Table(name="sqvf_dossiers_agent_verificateur")
 * @ORM\Entity(repositoryClass="SQVFBundle\Repository\sqvf_dossiers_agent_verificateurRepository")
 */
class sqvf_dossiers_agent_verificateur
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
     * @var int
     *
     * @ORM\Column(name="id_dossier", type="integer", nullable=true)
     */
    private $idDossier;

    /**
     * @var int
     *
     * @ORM\Column(name="id_agent_verificateur", type="integer", nullable=true)
     */
    private $idAgentVerificateur;


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
     * Set idDossier
     *
     * @param integer $idDossier
     *
     * @return AgentVerificateur
     */
    public function setIdDossier($idDossier)
    {
        $this->idDossier = $idDossier;

        return $this;
    }

    /**
     * Get idDossier
     *
     * @return int
     */
    public function getIdDossier()
    {
        return $this->idDossier;
    }

    /**
     * Set idAgentVerificateur
     *
     * @param integer $idAgentVerificateur
     *
     * @return AgentVerificateur
     */
    public function setIdAgentVerificateur($idAgentVerificateur)
    {
        $this->idAgentVerificateur = $idAgentVerificateur;

        return $this;
    }

    /**
     * Get idAgentVerificateur
     *
     * @return int
     */
    public function getIdAgentVerificateur()
    {
        return $this->idAgentVerificateur;
    }
}

