<?php

namespace SQVFBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * sqvf_dossiers_references
 *
 * @ORM\Table(name="sqvf_dossiers_references")
 * @ORM\Entity(repositoryClass="SQVFBundle\Repository\sqvf_dossiers_referencesRepository")
 */
class sqvf_dossiers_references
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
     * @var string
     *
     * @ORM\Column(name="demande_eclaircissement", type="text", nullable=true)
     */
    private $demandeEclaircissement;

    /**
     * @var string
     *
     * @ORM\Column(name="demande_eclaircissement_lettre_de_reponse", type="text", nullable=true)
     */
    private $demandeEclaircissementLettreDeReponse;

    /**
     * @var string
     *
     * @ORM\Column(name="notification_primitive", type="text", nullable=true)
     */
    private $notificationPrimitive;

    /**
     * @var string
     *
     * @ORM\Column(name="notification_primitive_reponse_contribuable", type="text", nullable=true)
     */
    private $notificationPrimitiveReponseContribuable;

    /**
     * @var string
     *
     * @ORM\Column(name="notification_redressement", type="text", nullable=true)
     */
    private $notificationRedressement;

    /**
     * @var string
     *
     * @ORM\Column(name="notification_redressement_reponse_contribuable", type="text", nullable=true)
     */
    private $notificationRedressementReponseContribuable;

    /**
     * @var string
     *
     * @ORM\Column(name="notification_definitive", type="text", nullable=true)
     */
    private $notificationDefinitive;

    /**
     * @var string
     *
     * @ORM\Column(name="notification_definitive_reponse_contribuable", type="text", nullable=true)
     */
    private $notificationDefinitiveReponseContribuable;


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
     * @return sqvf_dossiers_references
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
     * Set demandeEclaircissement
     *
     * @param string $demandeEclaircissement
     *
     * @return sqvf_dossiers_references
     */
    public function setDemandeEclaircissement($demandeEclaircissement)
    {
        $this->demandeEclaircissement = $demandeEclaircissement;

        return $this;
    }

    /**
     * Get demandeEclaircissement
     *
     * @return string
     */
    public function getDemandeEclaircissement()
    {
        return $this->demandeEclaircissement;
    }

    /**
     * Set demandeEclaircissementLettreDeReponse
     *
     * @param string $demandeEclaircissementLettreDeReponse
     *
     * @return sqvf_dossiers_references
     */
    public function setDemandeEclaircissementLettreDeReponse($demandeEclaircissementLettreDeReponse)
    {
        $this->demandeEclaircissementLettreDeReponse = $demandeEclaircissementLettreDeReponse;

        return $this;
    }

    /**
     * Get demandeEclaircissementLettreDeReponse
     *
     * @return string
     */
    public function getDemandeEclaircissementLettreDeReponse()
    {
        return $this->demandeEclaircissementLettreDeReponse;
    }

    /**
     * Set notificationPrimitive
     *
     * @param string $notificationPrimitive
     *
     * @return sqvf_dossiers_references
     */
    public function setNotificationPrimitive($notificationPrimitive)
    {
        $this->notificationPrimitive = $notificationPrimitive;

        return $this;
    }

    /**
     * Get notificationPrimitive
     *
     * @return string
     */
    public function getNotificationPrimitive()
    {
        return $this->notificationPrimitive;
    }

    /**
     * Set notificationPrimitiveReponseContribuable
     *
     * @param string $notificationPrimitiveReponseContribuable
     *
     * @return sqvf_dossiers_references
     */
    public function setNotificationPrimitiveReponseContribuable($notificationPrimitiveReponseContribuable)
    {
        $this->notificationPrimitiveReponseContribuable = $notificationPrimitiveReponseContribuable;

        return $this;
    }

    /**
     * Get notificationPrimitiveReponseContribuable
     *
     * @return string
     */
    public function getNotificationPrimitiveReponseContribuable()
    {
        return $this->notificationPrimitiveReponseContribuable;
    }

    /**
     * Set notificationRedressement
     *
     * @param string $notificationRedressement
     *
     * @return sqvf_dossiers_references
     */
    public function setNotificationRedressement($notificationRedressement)
    {
        $this->notificationRedressement = $notificationRedressement;

        return $this;
    }

    /**
     * Get notificationRedressement
     *
     * @return string
     */
    public function getNotificationRedressement()
    {
        return $this->notificationRedressement;
    }

    /**
     * Set notificationRedressementReponseContribuable
     *
     * @param string $notificationRedressementReponseContribuable
     *
     * @return sqvf_dossiers_references
     */
    public function setNotificationRedressementReponseContribuable($notificationRedressementReponseContribuable)
    {
        $this->notificationRedressementReponseContribuable = $notificationRedressementReponseContribuable;

        return $this;
    }

    /**
     * Get notificationRedressementReponseContribuable
     *
     * @return string
     */
    public function getNotificationRedressementReponseContribuable()
    {
        return $this->notificationRedressementReponseContribuable;
    }

    /**
     * Set notificationDefinitive
     *
     * @param string $notificationDefinitive
     *
     * @return sqvf_dossiers_references
     */
    public function setNotificationDefinitive($notificationDefinitive)
    {
        $this->notificationDefinitive = $notificationDefinitive;

        return $this;
    }

    /**
     * Get notificationDefinitive
     *
     * @return string
     */
    public function getNotificationDefinitive()
    {
        return $this->notificationDefinitive;
    }

    /**
     * Set notificationDefinitiveReponseContribuable
     *
     * @param string $notificationDefinitiveReponseContribuable
     *
     * @return sqvf_dossiers_references
     */
    public function setNotificationDefinitiveReponseContribuable($notificationDefinitiveReponseContribuable)
    {
        $this->notificationDefinitiveReponseContribuable = $notificationDefinitiveReponseContribuable;

        return $this;
    }

    /**
     * Get notificationDefinitiveReponseContribuable
     *
     * @return string
     */
    public function getNotificationDefinitiveReponseContribuable()
    {
        return $this->notificationDefinitiveReponseContribuable;
    }
}

