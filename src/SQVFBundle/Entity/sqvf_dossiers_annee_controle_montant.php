<?php

namespace SQVFBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * sqvf_dossiers_annee_controle_montant
 *
 * @ORM\Table(name="sqvf_dossiers_annee_controle_montant")
 * @ORM\Entity(repositoryClass="SQVFBundle\Repository\sqvf_dossiers_annee_controle_montantRepository")
 */
class sqvf_dossiers_annee_controle_montant
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
     * @ORM\Column(name="id_dossier_annee_controle", type="integer", nullable=true)
     */
    private $idDossierAnneeControle;

    /**
     * @var int
     *
     * @ORM\Column(name="id_etape", type="integer", nullable=true)
     */
    private $idEtape;

    /**
     * @var string
     *
     * @ORM\Column(name="montant_principal", type="string", length=255, nullable=true)
     */
    private $montantPrincipal;

    /**
     * @var string
     *
     * @ORM\Column(name="montant_amende", type="string", length=255, nullable=true)
     */
    private $montantAmende;

    /**
     * @var string
     *
     * @ORM\Column(name="montant_total", type="string", length=255, nullable=true)
     */
    private $montantTotal;

    private $nif;
    private $uniqid;
    private $anneeControle;
    private $typeControle;
    private $numeroNotificationDefinitive;
    private $dateNotificationDefinitive;
    private $typeImpot;
    private $etapeCourante;
    private $numeroNotificationPrimitive;
    private $dateNotificationPrimitive;
    private $verificateur;
    private $numeroAvisDeVerification;
    private $dateAvisDeVerification;

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
     * @return sqvf_dossiers_annee_controle_montant
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
     * Set idDossierAnneeControle
     *
     * @param integer $idDossierAnneeControle
     *
     * @return sqvf_dossiers_annee_controle_montant
     */
    public function setIdDossierAnneeControle($idDossierAnneeControle)
    {
        $this->idDossierAnneeControle = $idDossierAnneeControle;

        return $this;
    }

    /**
     * Get idDossierAnneeControle
     *
     * @return int
     */
    public function getIdDossierAnneeControle()
    {
        return $this->idDossierAnneeControle;
    }

    /**
     * Set idEtape
     *
     * @param integer $idEtape
     *
     * @return sqvf_dossiers_annee_controle_montant
     */
    public function setIdEtape($idEtape)
    {
        $this->idEtape = $idEtape;

        return $this;
    }

    /**
     * Get idEtape
     *
     * @return int
     */
    public function getIdEtape()
    {
        return $this->idEtape;
    }

    /**
     * Set montantPrincipal
     *
     * @param string $montantPrincipal
     *
     * @return sqvf_dossiers_annee_controle_montant
     */
    public function setMontantPrincipal($montantPrincipal)
    {
        $this->montantPrincipal = $montantPrincipal;

        return $this;
    }

    /**
     * Get montantPrincipal
     *
     * @return string
     */
    public function getMontantPrincipal()
    {
        return $this->montantPrincipal;
    }

    /**
     * Set montantAmende
     *
     * @param string $montantAmende
     *
     * @return sqvf_dossiers_annee_controle_montant
     */
    public function setMontantAmende($montantAmende)
    {
        $this->montantAmende = $montantAmende;

        return $this;
    }

    /**
     * Get montantAmende
     *
     * @return string
     */
    public function getMontantAmende()
    {
        return $this->montantAmende;
    }

    /**
     * Set montantTotal
     *
     * @param string $montantTotal
     *
     * @return sqvf_dossiers_annee_controle_montant
     */
    public function setMontantTotal($montantTotal)
    {
        $this->montantTotal = $montantTotal;

        return $this;
    }

    /**
     * Get montantTotal
     *
     * @return string
     */
    public function getMontantTotal()
    {
        return $this->montantTotal;
    }

    /**
     * Set nif
     *
     * @param string $nif
     *
     * @return sqvf_dossiers_annee_controle_montant
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
     * Set uniqid
     *
     * @param string $uniqid
     *
     * @return sqvf_dossiers_annee_controle_montant
     */
    public function setUniqid($uniqid)
    {
        $this->uniqid = $uniqid;

        return $this;
    }

    /**
     * Get uniqid
     *
     * @return string
     */
    public function getUniqid()
    {
        return $this->uniqid;
    }

    /**
     * Set anneeControle
     *
     * @param integer $anneeControle
     *
     * @return sqvf_dossiers_annee_controle_montant
     */
    public function setAnneeControle($anneeControle)
    {
        $this->anneeControle = $anneeControle;

        return $this;
    }

    /**
     * Get anneeControle
     *
     * @return int
     */
    public function getAnneeControle()
    {
        return $this->anneeControle;
    }

    /**
     * Set typeControle
     *
     * @param string $typeControle
     *
     * @return sqvf_dossiers_annee_controle_montant
     */
    public function setTypeControle($typeControle)
    {
        $this->typeControle = $typeControle;

        return $this;
    }

    /**
     * Get typeControle
     *
     * @return string
     */
    public function getTypeControle()
    {
        return $this->typeControle;
    }

    /**
     * Set numeroNotificationDefinitive
     *
     * @param integer $numeroNotificationDefinitive
     *
     * @return sqvf_dossiers_annee_controle_montant
     */
    public function setNumeroNotificationDefinitive($numeroNotificationDefinitive)
    {
        $this->numeroNotificationDefinitive = $numeroNotificationDefinitive;

        return $this;
    }

    /**
     * Get numeroNotificationDefinitive
     *
     * @return int
     */
    public function getNumeroNotificationDefinitive()
    {
        return $this->numeroNotificationDefinitive;
    }

    /**
     * Set dateNotificationDefinitive
     *
     * @param date $dateNotificationDefinitive
     *
     * @return sqvf_dossiers_annee_controle_montant
     */
    public function setDateNotificationDefinitive($dateNotificationDefinitive)
    {
        $this->dateNotificationDefinitive = $dateNotificationDefinitive;

        return $this;
    }

    /**
     * Get dateNotificationDefinitive
     *
     * @return date
     */
    public function getDateNotificationDefinitive()
    {
        return $this->dateNotificationDefinitive;
    }

    /**
     * Set typeImpot
     *
     * @param integer $typeImpot
     *
     * @return sqvf_dossiers_annee_controle_montant
     */
    public function setTypeImpot($typeImpot)
    {
        $this->typeImpot = $typeImpot;

        return $this;
    }

    /**
     * Get typeImpot
     *
     * @return int
     */
    public function getTypeImpot()
    {
        return $this->typeImpot;
    }

    /**
     * Set etapeCourante
     *
     * @param integer $etapeCourante
     *
     * @return sqvf_dossiers_annee_controle_montant
     */
    public function setEtapeCourante($etapeCourante)
    {
        $this->etapeCourante = $etapeCourante;

        return $this;
    }

    /**
     * Get etapeCourante
     *
     * @return int
     */
    public function getEtapeCourante()
    {
        return $this->etapeCourante;
    }

    /**
     * Set numeroNotificationPrimitive
     *
     * @param integer $numeroNotificationPrimitive
     *
     * @return sqvf_dossiers_annee_controle_montant
     */
    public function setNumeroNotificationPrimitive($numeroNotificationPrimitive)
    {
        $this->numeroNotificationPrimitive = $numeroNotificationPrimitive;

        return $this;
    }

    /**
     * Get numeroNotificationPrimitive
     *
     * @return int
     */
    public function getNumeroNotificationPrimitive()
    {
        return $this->numeroNotificationPrimitive;
    }

    /**
     * Set dateNotificationPrimitive
     *
     * @param date $dateNotificationPrimitive
     *
     * @return sqvf_dossiers_annee_controle_montant
     */
    public function setDateNotificationPrimitive($dateNotificationPrimitive)
    {
        $this->dateNotificationPrimitive = $dateNotificationPrimitive;

        return $this;
    }

    /**
     * Get dateNotificationPrimitive
     *
     * @return date
     */
    public function getDateNotificationPrimitive()
    {
        return $this->dateNotificationPrimitive;
    }

    /**
     * Set numeroAvisDeVerification
     *
     * @param integer $numeroAvisDeVerification
     *
     * @return sqvf_dossiers_annee_controle_montant
     */
    public function setNumeroAvisDeVerification($numeroAvisDeVerification)
    {
        $this->numeroAvisDeVerification = $numeroAvisDeVerification;

        return $this;
    }

    /**
     * Get numeroAvisDeVerification
     *
     * @return int
     */
    public function getNumeroAvisDeVerification()
    {
        return $this->numeroAvisDeVerification;
    }

    /**
     * Set dateAvisDeVerification
     *
     * @param date $dateAvisDeVerification
     *
     * @return sqvf_dossiers_annee_controle_montant
     */
    public function setDateAvisDeVerification($dateAvisDeVerification)
    {
        $this->dateAvisDeVerification = $dateAvisDeVerification;

        return $this;
    }

    /**
     * Get dateAvisDeVerification
     *
     * @return date
     */
    public function getDateAvisDeVerification()
    {
        return $this->dateAvisDeVerification;
    }

    /**
     * Set verificateur
     *
     * @param string $verificateur
     *
     * @return sqvf_dossiers_annee_controle_montant
     */
    public function setVerificateur($verificateur)
    {
        $this->verificateur = $verificateur;

        return $this;
    }

    /**
     * Get verificateur
     *
     * @return string
     */
    public function getVerificateur()
    {
        return $this->verificateur;
    }

}