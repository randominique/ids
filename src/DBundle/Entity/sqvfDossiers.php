<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * sqvfDossiers
 *
 * @ORM\Table(name="sqvfDossiers")
 * @ORM\Entity(repositoryClass="DBundle\Repository\sqvfDossiersRepository")
 */
class sqvfDossiers
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
     * @ORM\Column(name="id_user", type="integer", nullable=true)
     */
    private $idUser;

    /**
     * @var int
     *
     * @ORM\Column(name="id_centre_fiscal", type="integer", nullable=true)
     */
    private $idCentreFiscal;

    /**
     * @var string
     *
     * @ORM\Column(name="centre_fiscal", type="string", nullable=true)
     */
    private $centreFiscal;

    /**
     * @var int
     *
     * @ORM\Column(name="id_type_notification", type="integer", nullable=true)
     */
    private $idTypeNotification;

    /**
     * @var int
     *
     * @ORM\Column(name="id_notification_redressement", type="integer", nullable=true)
     */
    private $idNotificationRedressement;

    /**
     * @var int
     *
     * @ORM\Column(name="id_notification_definitive", type="integer", nullable=true)
     */
    private $idNotificationDefinitive;

    /**
     * @var int
     *
     * @ORM\Column(name="nif", type="integer", nullable=true)
     */
    private $nif;

    /**
     * @var int
     *
     * @ORM\Column(name="uniqid", type="string", nullable=true)
     */
    private $uniqid;

    /**
     * @var string
     *
     * @ORM\Column(name="type_controle", type="string", length=50, nullable=true)
     */
    private $typeControle;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut_operation", type="date", nullable=true)
     */
    private $dateDebutOperation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="date", nullable=true)
     */
    private $dateCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut_intervention", type="date", nullable=true)
     */
    private $dateDebutIntervention;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin_intervention", type="date", nullable=true)
     */
    private $dateFinIntervention;

    /**
     * @var string
     *
     * @ORM\Column(name="etape_courante", type="string", length=50, nullable=true)
     */
    private $etapeCourante;

    /**
     * @var int
     *
     * @ORM\Column(name="archive", type="integer", nullable=true)
     */
    private $archive;

    /**
     * @var int
     *
     * @ORM\Column(name="new_uniqid", type="integer", nullable=true)
     */
    private $newUniqid;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=true)
     */
    private $createTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_time", type="datetime", nullable=true)
     */
    private $updateTime;

    /**
     * @var string
     *
     * @ORM\Column(name="raison_sociale", type="string", length=50, nullable=true)
     */
    private $rs;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=50, nullable=true)
     */
    private $adresse;

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
     * Set idDossier
     *
     * @param integer $idDossier
     *
     * @return sqvfDossiers
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
     * Set idUser
     *
     * @param integer $idUser
     *
     * @return sqvfDossiers
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
        return $this->idUser;
    }

    /**
     * Set idCentreFiscal
     *
     * @param integer $idCentreFiscal
     *
     * @return sqvfDossiers
     */
    public function setIdCentreFiscal($idCentreFiscal)
    {
        $this->idCentreFiscal = $idCentreFiscal;

        return $this;
    }

    /**
     * Get idCentreFiscal
     *
     * @return int
     */
    public function getIdCentreFiscal()
    {
        return $this->idCentreFiscal;
    }

    /**
     * Set centreFiscal
     *
     * @param string $centreFiscal
     *
     * @return sqvfDossiers
     */
    public function setCentreFiscal($centreFiscal)
    {
        $this->centreFiscal = $centreFiscal;

        return $this;
    }

    /**
     * Get centreFiscal
     *
     * @return string
     */
    public function getCentreFiscal()
    {
        return $this->centreFiscal;
    }

    /**
     * Set idTypeNotification
     *
     * @param integer $idTypeNotification
     *
     * @return sqvfDossiers
     */
    public function setIdTypeNotification($idTypeNotification)
    {
        $this->idTypeNotification = $idTypeNotification;

        return $this;
    }

    /**
     * Get idTypeNotification
     *
     * @return int
     */
    public function getIdTypeNotification()
    {
        return $this->idTypeNotification;
    }

    /**
     * Set idNotificationRedressement
     *
     * @param integer $idNotificationRedressement
     *
     * @return sqvfDossiers
     */
    public function setIdNotificationRedressement($idNotificationRedressement)
    {
        $this->idNotificationRedressement = $idNotificationRedressement;

        return $this;
    }

    /**
     * Get idNotificationRedressement
     *
     * @return int
     */
    public function getIdNotificationRedressement()
    {
        return $this->idNotificationRedressement;
    }

    /**
     * Set idNotificationDefinitive
     *
     * @param integer $idNotificationDefinitive
     *
     * @return sqvfDossiers
     */
    public function setIdNotificationDefinitive($idNotificationDefinitive)
    {
        $this->idNotificationDefinitive = $idNotificationDefinitive;

        return $this;
    }

    /**
     * Get idNotificationDefinitive
     *
     * @return int
     */
    public function getIdNotificationDefinitive()
    {
        return $this->idNotificationDefinitive;
    }

    /**
     * Set nif
     *
     * @param integer $nif
     *
     * @return sqvfDossiers
     */
    public function setNif($nif)
    {
        $this->nif = $nif;

        return $this;
    }

    /**
     * Get nif
     *
     * @return int
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
     * @return sqvfDossiers
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
     * Set typeControle
     *
     * @param string $typeControle
     *
     * @return sqvfDossiers
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
     * Set dateDebutOperation
     *
     * @param \DateTime $dateDebutOperation
     *
     * @return sqvfDossiers
     */
    public function setDateDebutOperation($dateDebutOperation)
    {
        $this->dateDebutOperation = $dateDebutOperation;

        return $this;
    }

    /**
     * Get dateDebutOperation
     *
     * @return \DateTime
     */
    public function getDateDebutOperation()
    {
        return $this->dateDebutOperation;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return sqvfDossiers
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set dateDebutIntervention
     *
     * @param \DateTime $dateDebutIntervention
     *
     * @return sqvfDossiers
     */
    public function setDateDebutIntervention($dateDebutIntervention)
    {
        $this->dateDebutIntervention = $dateDebutIntervention;

        return $this;
    }

    /**
     * Get dateDebutIntervention
     *
     * @return \DateTime
     */
    public function getDateDebutIntervention()
    {
        return $this->dateDebutIntervention;
    }

    /**
     * Set dateFinIntervention
     *
     * @param \DateTime $dateFinIntervention
     *
     * @return sqvfDossiers
     */
    public function setDateFinIntervention($dateFinIntervention)
    {
        $this->dateFinIntervention = $dateFinIntervention;

        return $this;
    }

    /**
     * Get dateFinIntervention
     *
     * @return \DateTime
     */
    public function getDateFinIntervention()
    {
        return $this->dateFinIntervention;
    }

    /**
     * Set etapeCourante
     *
     * @param string $etapeCourante
     *
     * @return sqvfDossiers
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
     * Set archive
     *
     * @param integer $archive
     *
     * @return sqvfDossiers
     */
    public function setArchive($archive)
    {
        $this->archive = $archive;

        return $this;
    }

    /**
     * Get archive
     *
     * @return int
     */
    public function getArchive()
    {
        return $this->archive;
    }

    /**
     * Set newUniqid
     *
     * @param integer $newUniqid
     *
     * @return sqvfDossiers
     */
    public function setNewUniqid($newUniqid)
    {
        $this->newUniqid = $newUniqid;

        return $this;
    }

    /**
     * Get newUniqid
     *
     * @return int
     */
    public function getNewUniqid()
    {
        return $this->newUniqid;
    }

    /**
     * Set createTime
     *
     * @param \DateTime $createTime
     *
     * @return sqvfDossiers
     */
    public function setCreateTime($createTime)
    {
        $this->createTime = $createTime;

        return $this;
    }

    /**
     * Get createTime
     *
     * @return \DateTime
     */
    public function getCreateTime()
    {
        return $this->createTime;
    }

    /**
     * Set updateTime
     *
     * @param \DateTime $updateTime
     *
     * @return sqvfDossiers
     */
    public function setUpdateTime($updateTime)
    {
        $this->updateTime = $updateTime;

        return $this;
    }

    /**
     * Get updateTime
     *
     * @return \DateTime
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
    }

    /**
     * Set rs
     *
     * @param string $rs
     *
     * @return sqvf_dossiers
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
     * Set adresse
     *
     * @param string $adresse
     *
     * @return sqvf_dossiers
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;

        return $this;
    }

    /**
     * Get adresse
     *
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set montantPrincipal
     *
     * @param integer $montantPrincipal
     *
     * @return sqvf_dossiers
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
     * @return sqvf_dossiers
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
     * @return sqvf_dossiers
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

