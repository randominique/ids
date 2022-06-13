<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VerificationsEnCours
 *
 * @ORM\Table(name="VerificationsEnCours")
 * @ORM\Entity(repositoryClass="DBundle\Repository\VerificationsEnCoursRepository")
 */
class VerificationsEnCours
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
     * @ORM\Column(name="id_user", type="integer", nullable=true)
     */
    private $idUser;

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
     * @var string
     *
     * @ORM\Column(name="uniqid", type="string", length=50, nullable=true)
     */
    private $uniqid;

    /**
     * @var string
     *
     * @ORM\Column(name="nif", type="string", length=20, nullable=true)
     */
    private $nif;

    /**
     * @var int
     *
     * @ORM\Column(name="annee_controle", type="integer", nullable=true)
     */
    private $anneeControle;

    /**
     * @var int
     *
     * @ORM\Column(name="numero_avis_de_verification", type="integer", nullable=true)
     */
    private $numeroAvisDeVerification;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_avis_de_verification", type="date", nullable=true)
     */
    private $dateAvisDeVerification;

    /**
     * @var string
     *
     * @ORM\Column(name="verificateur", type="string", length=250, nullable=true)
     */
    private $verificateur;

    /**
     * @var string
     *
     * @ORM\Column(name="type_controle", type="string", length=50, nullable=true)
     */
    private $typeControle;

    /**
     * @var int
     *
     * @ORM\Column(name="numero_notification_primitive", type="integer", nullable=true)
     */
    private $numeroNotificationPrimitive;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_notification_primitive", type="date", nullable=true)
     */
    private $dateNotificationPrimitive;

    /**
     * @var string
     *
     * @ORM\Column(name="type_impot", type="string", length=255, nullable=true)
     */
    private $typeImpot;

    /**
     * @var int
     *
     * @ORM\Column(name="id_etape", type="integer", nullable=true)
     */
    private $idEtape;

    /**
     * @var string
     *
     * @ORM\Column(name="etape_courante", type="string", length=255, nullable=true)
     */
    private $etapeCourante;

    /**
     * @var int
     *
     * @ORM\Column(name="montant_principal", type="integer", nullable=true)
     */
    private $montantPrincipal;

    /**
     * @var int
     *
     * @ORM\Column(name="montant_amende", type="integer", nullable=true)
     */
    private $montantAmende;

    /**
     * @var int
     *
     * @ORM\Column(name="montant_total", type="integer", nullable=true)
     */
    private $montantTotal;


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
     * Set idUser
     *
     * @param integer $idUser
     *
     * @return VerificationsEnCours
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return int
     */
    public function getIdUser()
    {
        return $this->idDossier;
    }

    /**
     * Set idDossier
     *
     * @param integer $idDossier
     *
     * @return VerificationsEnCours
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
     * @return VerificationsEnCours
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
     * Set uniqid
     *
     * @param string $uniqid
     *
     * @return VerificationsEnCours
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
     * Set nif
     *
     * @param string $nif
     *
     * @return VerificationsEnCours
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
     * Set anneeControle
     *
     * @param integer $anneeControle
     *
     * @return VerificationsEnCours
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
     * Set numeroAvisDeVerification
     *
     * @param integer $numeroAvisDeVerification
     *
     * @return VerificationsEnCours
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
     * @param \DateTime $dateAvisDeVerification
     *
     * @return VerificationsEnCours
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
     * Set verificateur
     *
     * @param string $verificateur
     *
     * @return VerificationsEnCours
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

    /**
     * Set typeControle
     *
     * @param string $typeControle
     *
     * @return VerificationsEnCours
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
     * Set numeroNotificationPrimitive
     *
     * @param integer $numeroNotificationPrimitive
     *
     * @return VerificationsEnCours
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
     * @param \DateTime $dateNotificationPrimitive
     *
     * @return VerificationsEnCours
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
     * Set typeImpot
     *
     * @param string $typeImpot
     *
     * @return VerificationsEnCours
     */
    public function setTypeImpot($typeImpot)
    {
        $this->typeImpot = $typeImpot;

        return $this;
    }

    /**
     * Get typeImpot
     *
     * @return string
     */
    public function getTypeImpot()
    {
        return $this->typeImpot;
    }

    /**
     * Set idEtape
     *
     * @param integer $idEtape
     *
     * @return VerificationsEnCours
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
     * Set etapeCourante
     *
     * @param string $etapeCourante
     *
     * @return VerificationsEnCours
     */
    public function setEtapeCourante($etapeCourante)
    {
        $this->etapeCourante = $etapeCourante;

        return $this;
    }

    /**
     * Get etapeCourante
     *
     * @return string
     */
    public function getEtapeCourante()
    {
        return $this->etapeCourante;
    }

    /**
     * Set montantPrincipal
     *
     * @param integer $montantPrincipal
     *
     * @return VerificationsEnCours
     */
    public function setMontantPrincipal($montantPrincipal)
    {
        $this->montantPrincipal = $montantPrincipal;

        return $this;
    }

    /**
     * Get montantPrincipal
     *
     * @return int
     */
    public function getMontantPrincipal()
    {
        return $this->montantPrincipal;
    }

    /**
     * Set montantAmende
     *
     * @param integer $montantAmende
     *
     * @return VerificationsEnCours
     */
    public function setMontantAmende($montantAmende)
    {
        $this->montantAmende = $montantAmende;

        return $this;
    }

    /**
     * Get montantAmende
     *
     * @return int
     */
    public function getMontantAmende()
    {
        return $this->montantAmende;
    }

    /**
     * Set montantTotal
     *
     * @param integer $montantTotal
     *
     * @return VerificationsEnCours
     */
    public function setMontantTotal($montantTotal)
    {
        $this->montantTotal = $montantTotal;

        return $this;
    }

    /**
     * Get montantTotal
     *
     * @return int
     */
    public function getMontantTotal()
    {
        return $this->montantTotal;
    }
}

