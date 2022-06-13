<?php

namespace SQVFBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * sqvf_dossiers_etapes
 *
 * @ORM\Table(name="sqvf_dossiers_etapes")
 * @ORM\Entity(repositoryClass="SQVFBundle\Repository\sqvf_dossiers_etapesRepository")
 */
class sqvf_dossiers_etapes
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
     * @ORM\Column(name="details_dossier", type="integer", nullable=true)
     */
    private $detailsDossier;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_details_dossier", type="date", nullable=true)
     */
    private $dateDetailsDossier;

    /**
     * @var int
     *
     * @ORM\Column(name="demande_eclaircissement_central", type="integer", nullable=true)
     */
    private $demandeEclaircissementCentral;

    /**
     * @var int
     *
     * @ORM\Column(name="demande_eclaircissement", type="integer", nullable=true)
     */
    private $demandeEclaircissement;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_demande_eclaircissement", type="date", nullable=true)
     */
    private $dateDemandeEclaircissement;

    /**
     * @var int
     *
     * @ORM\Column(name="avis_de_verification", type="integer", nullable=true)
     */
    private $avisDeVerification;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_avis_de_verification", type="date", nullable=true)
     */
    private $dateAvisDeVerification;

    /**
     * @var int
     *
     * @ORM\Column(name="notification_primitive_central", type="integer", nullable=true)
     */
    private $notificationPrimitiveCentral;

    /**
     * @var int
     *
     * @ORM\Column(name="notification_primitive", type="integer", nullable=true)
     */
    private $notificationPrimitive;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_notification_primitive", type="date", nullable=true)
     */
    private $dateNotificationPrimitive;

    /**
     * @var int
     *
     * @ORM\Column(name="lettre_de_reponse", type="integer", nullable=true)
     */
    private $lettreDeReponse;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_lettre_de_reponse", type="date", nullable=true)
     */
    private $dateLettreDeReponse;

    /**
     * @var int
     *
     * @ORM\Column(name="notification_redressement_central", type="integer", nullable=true)
     */
    private $notificationRedressementCentral;

    /**
     * @var int
     *
     * @ORM\Column(name="notification_redressement", type="integer", nullable=true)
     */
    private $notificationRedressement;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_notification_redressement", type="date", nullable=true)
     */
    private $dateNotificationRedressement;

    /**
     * @var int
     *
     * @ORM\Column(name="commission_fiscale", type="integer", nullable=true)
     */
    private $commissionFiscale;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_commission_fiscale", type="date", nullable=true)
     */
    private $dateCommissionFiscale;

    /**
     * @var int
     *
     * @ORM\Column(name="notification_definitive_central", type="integer", nullable=true)
     */
    private $notificationDefinitiveCentral;

    /**
     * @var int
     *
     * @ORM\Column(name="notification_definitive", type="integer", nullable=true)
     */
    private $notificationDefinitive;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_notification_definitive", type="date", nullable=true)
     */
    private $dateNotificationDefinitive;

    /**
     * @var int
     *
     * @ORM\Column(name="reclamation", type="integer", nullable=true)
     */
    private $reclamation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_reclamation", type="date", nullable=true)
     */
    private $dateReclamation;

    /**
     * @var int
     *
     * @ORM\Column(name="recouvrement", type="integer", nullable=true)
     */
    private $recouvrement;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_recouvrement", type="date", nullable=true)
     */
    private $dateRecouvrement;

    /**
     * @var int
     *
     * @ORM\Column(name="autres_pieces_jointes", type="integer", nullable=true)
     */
    private $autresPiecesJointes;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_autres_pieces_jointes", type="date", nullable=true)
     */
    private $dateAutresPiecesJointes;


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
     * @return sqvf_dossiers_etapes
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
     * Set detailsDossier
     *
     * @param integer $detailsDossier
     *
     * @return sqvf_dossiers_etapes
     */
    public function setDetailsDossier($detailsDossier)
    {
        $this->detailsDossier = $detailsDossier;

        return $this;
    }

    /**
     * Get detailsDossier
     *
     * @return int
     */
    public function getDetailsDossier()
    {
        return $this->detailsDossier;
    }

    /**
     * Set dateDetailsDossier
     *
     * @param \DateTime $dateDetailsDossier
     *
     * @return sqvf_dossiers_etapes
     */
    public function setDateDetailsDossier($dateDetailsDossier)
    {
        $this->dateDetailsDossier = $dateDetailsDossier;

        return $this;
    }

    /**
     * Get dateDetailsDossier
     *
     * @return \DateTime
     */
    public function getDateDetailsDossier()
    {
        return $this->dateDetailsDossier;
    }

    /**
     * Set demandeEclaircissementCentral
     *
     * @param integer $demandeEclaircissementCentral
     *
     * @return sqvf_dossiers_etapes
     */
    public function setDemandeEclaircissementCentral($demandeEclaircissementCentral)
    {
        $this->demandeEclaircissementCentral = $demandeEclaircissementCentral;

        return $this;
    }

    /**
     * Get demandeEclaircissementCentral
     *
     * @return int
     */
    public function getDemandeEclaircissementCentral()
    {
        return $this->demandeEclaircissementCentral;
    }

    /**
     * Set demandeEclaircissement
     *
     * @param integer $demandeEclaircissement
     *
     * @return sqvf_dossiers_etapes
     */
    public function setDemandeEclaircissement($demandeEclaircissement)
    {
        $this->demandeEclaircissement = $demandeEclaircissement;

        return $this;
    }

    /**
     * Get demandeEclaircissement
     *
     * @return int
     */
    public function getDemandeEclaircissement()
    {
        return $this->demandeEclaircissement;
    }

    /**
     * Set dateDemandeEclaircissement
     *
     * @param \DateTime $dateDemandeEclaircissement
     *
     * @return sqvf_dossiers_etapes
     */
    public function setDateDemandeEclaircissement($dateDemandeEclaircissement)
    {
        $this->dateDemandeEclaircissement = $dateDemandeEclaircissement;

        return $this;
    }

    /**
     * Get dateDemandeEclaircissement
     *
     * @return \DateTime
     */
    public function getDateDemandeEclaircissement()
    {
        return $this->dateDemandeEclaircissement;
    }

    /**
     * Set avisDeVerification
     *
     * @param integer $avisDeVerification
     *
     * @return sqvf_dossiers_etapes
     */
    public function setAvisDeVerification($avisDeVerification)
    {
        $this->avisDeVerification = $avisDeVerification;

        return $this;
    }

    /**
     * Get avisDeVerification
     *
     * @return int
     */
    public function getAvisDeVerification()
    {
        return $this->avisDeVerification;
    }

    /**
     * Set dateAvisDeVerification
     *
     * @param \DateTime $dateAvisDeVerification
     *
     * @return sqvf_dossiers_etapes
     */
    public function setDateAvisDeVerification($dateAvisDeVerification)
    {
        $this->dateAvisDeVerification = $dateAvisDeVerification;

        return $this;
    }

    /**
     * Get dateAvisDeVerification
     *
     * @return \DateTime
     */
    public function getDateAvisDeVerification()
    {
        return $this->dateAvisDeVerification;
    }

    /**
     * Set notificationPrimitiveCentral
     *
     * @param integer $notificationPrimitiveCentral
     *
     * @return sqvf_dossiers_etapes
     */
    public function setNotificationPrimitiveCentral($notificationPrimitiveCentral)
    {
        $this->notificationPrimitiveCentral = $notificationPrimitiveCentral;

        return $this;
    }

    /**
     * Get notificationPrimitiveCentral
     *
     * @return int
     */
    public function getNotificationPrimitiveCentral()
    {
        return $this->notificationPrimitiveCentral;
    }

    /**
     * Set notificationPrimitive
     *
     * @param integer $notificationPrimitive
     *
     * @return sqvf_dossiers_etapes
     */
    public function setNotificationPrimitive($notificationPrimitive)
    {
        $this->notificationPrimitive = $notificationPrimitive;

        return $this;
    }

    /**
     * Get notificationPrimitive
     *
     * @return int
     */
    public function getNotificationPrimitive()
    {
        return $this->notificationPrimitive;
    }

    /**
     * Set dateNotificationPrimitive
     *
     * @param \DateTime $dateNotificationPrimitive
     *
     * @return sqvf_dossiers_etapes
     */
    public function setDateNotificationPrimitive($dateNotificationPrimitive)
    {
        $this->dateNotificationPrimitive = $dateNotificationPrimitive;

        return $this;
    }

    /**
     * Get dateNotificationPrimitive
     *
     * @return \DateTime
     */
    public function getDateNotificationPrimitive()
    {
        return $this->dateNotificationPrimitive;
    }

    /**
     * Set lettreDeReponse
     *
     * @param integer $lettreDeReponse
     *
     * @return sqvf_dossiers_etapes
     */
    public function setLettreDeReponse($lettreDeReponse)
    {
        $this->lettreDeReponse = $lettreDeReponse;

        return $this;
    }

    /**
     * Get lettreDeReponse
     *
     * @return int
     */
    public function getLettreDeReponse()
    {
        return $this->lettreDeReponse;
    }

    /**
     * Set dateLettreDeReponse
     *
     * @param \DateTime $dateLettreDeReponse
     *
     * @return sqvf_dossiers_etapes
     */
    public function setDateLettreDeReponse($dateLettreDeReponse)
    {
        $this->dateLettreDeReponse = $dateLettreDeReponse;

        return $this;
    }

    /**
     * Get dateLettreDeReponse
     *
     * @return \DateTime
     */
    public function getDateLettreDeReponse()
    {
        return $this->dateLettreDeReponse;
    }

    /**
     * Set notificationRedressementCentral
     *
     * @param integer $notificationRedressementCentral
     *
     * @return sqvf_dossiers_etapes
     */
    public function setNotificationRedressementCentral($notificationRedressementCentral)
    {
        $this->notificationRedressementCentral = $notificationRedressementCentral;

        return $this;
    }

    /**
     * Get notificationRedressementCentral
     *
     * @return int
     */
    public function getNotificationRedressementCentral()
    {
        return $this->notificationRedressementCentral;
    }

    /**
     * Set notificationRedressement
     *
     * @param integer $notificationRedressement
     *
     * @return sqvf_dossiers_etapes
     */
    public function setNotificationRedressement($notificationRedressement)
    {
        $this->notificationRedressement = $notificationRedressement;

        return $this;
    }

    /**
     * Get notificationRedressement
     *
     * @return int
     */
    public function getNotificationRedressement()
    {
        return $this->notificationRedressement;
    }

    /**
     * Set dateNotificationRedressement
     *
     * @param \DateTime $dateNotificationRedressement
     *
     * @return sqvf_dossiers_etapes
     */
    public function setDateNotificationRedressement($dateNotificationRedressement)
    {
        $this->dateNotificationRedressement = $dateNotificationRedressement;

        return $this;
    }

    /**
     * Get dateNotificationRedressement
     *
     * @return \DateTime
     */
    public function getDateNotificationRedressement()
    {
        return $this->dateNotificationRedressement;
    }

    /**
     * Set commissionFiscale
     *
     * @param integer $commissionFiscale
     *
     * @return sqvf_dossiers_etapes
     */
    public function setCommissionFiscale($commissionFiscale)
    {
        $this->commissionFiscale = $commissionFiscale;

        return $this;
    }

    /**
     * Get commissionFiscale
     *
     * @return int
     */
    public function getCommissionFiscale()
    {
        return $this->commissionFiscale;
    }

    /**
     * Set dateCommissionFiscale
     *
     * @param \DateTime $dateCommissionFiscale
     *
     * @return sqvf_dossiers_etapes
     */
    public function setDateCommissionFiscale($dateCommissionFiscale)
    {
        $this->dateCommissionFiscale = $dateCommissionFiscale;

        return $this;
    }

    /**
     * Get dateCommissionFiscale
     *
     * @return \DateTime
     */
    public function getDateCommissionFiscale()
    {
        return $this->dateCommissionFiscale;
    }

    /**
     * Set notificationDefinitiveCentral
     *
     * @param integer $notificationDefinitiveCentral
     *
     * @return sqvf_dossiers_etapes
     */
    public function setNotificationDefinitiveCentral($notificationDefinitiveCentral)
    {
        $this->notificationDefinitiveCentral = $notificationDefinitiveCentral;

        return $this;
    }

    /**
     * Get notificationDefinitiveCentral
     *
     * @return int
     */
    public function getNotificationDefinitiveCentral()
    {
        return $this->notificationDefinitiveCentral;
    }

    /**
     * Set notificationDefinitive
     *
     * @param integer $notificationDefinitive
     *
     * @return sqvf_dossiers_etapes
     */
    public function setNotificationDefinitive($notificationDefinitive)
    {
        $this->notificationDefinitive = $notificationDefinitive;

        return $this;
    }

    /**
     * Get notificationDefinitive
     *
     * @return int
     */
    public function getNotificationDefinitive()
    {
        return $this->notificationDefinitive;
    }

    /**
     * Set dateNotificationDefinitive
     *
     * @param \DateTime $dateNotificationDefinitive
     *
     * @return sqvf_dossiers_etapes
     */
    public function setDateNotificationDefinitive($dateNotificationDefinitive)
    {
        $this->dateNotificationDefinitive = $dateNotificationDefinitive;

        return $this;
    }

    /**
     * Get dateNotificationDefinitive
     *
     * @return \DateTime
     */
    public function getDateNotificationDefinitive()
    {
        return $this->dateNotificationDefinitive;
    }

    /**
     * Set reclamation
     *
     * @param integer $reclamation
     *
     * @return sqvf_dossiers_etapes
     */
    public function setReclamation($reclamation)
    {
        $this->reclamation = $reclamation;

        return $this;
    }

    /**
     * Get reclamation
     *
     * @return int
     */
    public function getReclamation()
    {
        return $this->reclamation;
    }

    /**
     * Set dateReclamation
     *
     * @param \DateTime $dateReclamation
     *
     * @return sqvf_dossiers_etapes
     */
    public function setDateReclamation($dateReclamation)
    {
        $this->dateReclamation = $dateReclamation;

        return $this;
    }

    /**
     * Get dateReclamation
     *
     * @return \DateTime
     */
    public function getDateReclamation()
    {
        return $this->dateReclamation;
    }

    /**
     * Set recouvrement
     *
     * @param integer $recouvrement
     *
     * @return sqvf_dossiers_etapes
     */
    public function setRecouvrement($recouvrement)
    {
        $this->recouvrement = $recouvrement;

        return $this;
    }

    /**
     * Get recouvrement
     *
     * @return int
     */
    public function getRecouvrement()
    {
        return $this->recouvrement;
    }

    /**
     * Set dateRecouvrement
     *
     * @param \DateTime $dateRecouvrement
     *
     * @return sqvf_dossiers_etapes
     */
    public function setDateRecouvrement($dateRecouvrement)
    {
        $this->dateRecouvrement = $dateRecouvrement;

        return $this;
    }

    /**
     * Get dateRecouvrement
     *
     * @return \DateTime
     */
    public function getDateRecouvrement()
    {
        return $this->dateRecouvrement;
    }

    /**
     * Set autresPiecesJointes
     *
     * @param integer $autresPiecesJointes
     *
     * @return sqvf_dossiers_etapes
     */
    public function setAutresPiecesJointes($autresPiecesJointes)
    {
        $this->autresPiecesJointes = $autresPiecesJointes;

        return $this;
    }

    /**
     * Get autresPiecesJointes
     *
     * @return int
     */
    public function getAutresPiecesJointes()
    {
        return $this->autresPiecesJointes;
    }

    /**
     * Set dateAutresPiecesJointes
     *
     * @param \DateTime $dateAutresPiecesJointes
     *
     * @return sqvf_dossiers_etapes
     */
    public function setDateAutresPiecesJointes($dateAutresPiecesJointes)
    {
        $this->dateAutresPiecesJointes = $dateAutresPiecesJointes;

        return $this;
    }

    /**
     * Get dateAutresPiecesJointes
     *
     * @return \DateTime
     */
    public function getDateAutresPiecesJointes()
    {
        return $this->dateAutresPiecesJointes;
    }
}

