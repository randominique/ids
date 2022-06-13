<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DossiersSQVF
 *
 * @ORM\Table(name="DossiersSQVF")
 * @ORM\Entity(repositoryClass="DBundle\Repository\DossiersSQVFRepository")
 */
class DossiersSQVF
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
     * @ORM\Column(name="id_dossier", type="integer")
     */
    private $idDossier;

    /**
     * @var int
     *
     * @ORM\Column(name="id_user", type="integer")
     */
    private $idUser;

    /**
     * @var int
     *
     * @ORM\Column(name="id_centre_fiscal", type="integer")
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
     * @ORM\Column(name="nif", type="integer", length=50, nullable=true)
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
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
     private $adresse;

    /**
     * @var int
     *
     * @ORM\Column(name="id_type_notification", type="integer")
     */
    private $idTypeNotification;

    /**
     * @var string
     *
     * @ORM\Column(name="typeNotification", type="string", length=255, nullable=true)
     */
    private $typeNotification;

    /**
     * @var int
     *
     * @ORM\Column(name="idNotificationRedressement", type="integer")
     */
    private $idNotificationRedressement;

    /**
     * @var string
     *
     * @ORM\Column(name="notificationRedressement", type="string", length=255, nullable=true)
     */
    private $notificationRedressement;

    /**
     * @var int
     *
     * @ORM\Column(name="idNotificationDefinitive", type="integer")
     */
    private $idNotificationDefinitive;

    /**
     * @var string
     *
     * @ORM\Column(name="notificationDefinitive", type="string", length=255, nullable=true)
     */
    private $notificationDefinitive;

    /**
     * @var int
     *
     * @ORM\Column(name="uniqid", type="string")
     */
    private $uniqid;

    /**
     * @var string
     *
     * @ORM\Column(name="typeControle", type="string", length=50, nullable=true)
     */
    private $typeControle;

    /**
     * @var string
     *
     * @ORM\Column(name="etapeCourante", type="string", length=50, nullable=true)
     */
    private $etapeCourante;

    /**
     * @var date
     *
     * @ORM\Column(name="date_debut_operation", type="date", nullable=true)
     */
    private $dateDebutOperation;

    /**
     * @var date
     *
     * @ORM\Column(name="date_creation", type="date", nullable=true)
     */
    private $dateCreation;

    /**
     * @var date
     *
     * @ORM\Column(name="date_debut_intervention", type="date", nullable=true)
     */
    private $dateDebutIntervention;

    /**
     * @var date
     *
     * @ORM\Column(name="date_fin_intervention", type="date", nullable=true)
     */
    private $dateFinIntervention;

    /**
     * @var int
     *
     * @ORM\Column(name="archive", type="integer", nullable=true)
     */
    private $archive;

    /**
     * @var int
     *
     * @ORM\Column(name="new_uniqid", type="integer")
     */
    private $newUniqid;

    /**
     * @var datetime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=true)
     */
    private $createTime;

    /**
     * @var datetime
     *
     * @ORM\Column(name="update_time", type="datetime", nullable=true)
     */
    private $updateTime;

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
     * @return DossiersSQVF
     */
    public function setIdDossier($idDossier)
    {
        $this->idDossier = $idDossier;

        return $this;
    }

    /**
     * Get idDossier
     *
     * @return integer
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
     * @return DossiersSQVF
     */
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get idUser
     *
     * @return integer
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
     * @return DossiersSQVF
     */
    public function setIdCentreFiscal($idCentreFiscal)
    {
        $this->idCentreFiscal = $idCentreFiscal;

        return $this;
    }

    /**
     * Get idCentreFiscal
     *
     * @return integer
     */
    public function getIdCentreFiscal()
    {
        return $this->idCentreFiscal;
    }

    /**
     * Set nif
     *
     * @param integer $nif
     *
     * @return DossiersSQVF
     */
    public function setNif($nif)
    {
        $this->nif = $nif;

        return $this;
    }

    /**
     * Get nif
     *
     * @return integer
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
     * @return DossiersSQVF
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
     * @return DossiersSQVF
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
     * Set centreFiscal
     *
     * @param string $centreFiscal
     *
     * @return DossiersSQVF
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
     * @return DossiersSQVF
     */
    public function setIdTypeNotification($idTypeNotification)
    {
        $this->idTypeNotification = $idTypeNotification;

        return $this;
    }

    /**
     * Get idTypeNotification
     *
     * @return integer
     */
    public function getIdTypeNotification()
    {
        return $this->idTypeNotification;
    }

    /**
     * Set typeNotification
     *
     * @param string $typeNotification
     *
     * @return DossiersSQVF
     */
    public function setTypeNotification($typeNotification)
    {
        $this->typeNotification = $typeNotification;

        return $this;
    }

    /**
     * Get typeNotification
     *
     * @return string
     */
    public function getTypeNotification()
    {
        return $this->typeNotification;
    }

    /**
     * Set idNotificationRedressement
     *
     * @param integer $idNotificationRedressement
     *
     * @return DossiersSQVF
     */
    public function setIdNotificationRedressement($idNotificationRedressement)
    {
        $this->idNotificationRedressement = $idNotificationRedressement;

        return $this;
    }

    /**
     * Get idNotificationRedressement
     *
     * @return integer
     */
    public function getIdNotificationRedressement()
    {
        return $this->idNotificationRedressement;
    }

    /**
     * Set notificationRedressement
     *
     * @param string $notificationRedressement
     *
     * @return DossiersSQVF
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
     * Set idNotificationDefinitive
     *
     * @param integer $idNotificationDefinitive
     *
     * @return DossiersSQVF
     */
    public function setIdNotificationDefinitive($idNotificationDefinitive)
    {
        $this->idNotificationDefinitive = $idNotificationDefinitive;

        return $this;
    }

    /**
     * Get idNotificationDefinitive
     *
     * @return integer
     */
    public function getIdNotificationDefinitive()
    {
        return $this->idNotificationDefinitive;
    }

    /**
     * Set notificationDefinitive
     *
     * @param string $notificationDefinitive
     *
     * @return DossiersSQVF
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
     * Set uniqid
     *
     * @param string $uniqid
     *
     * @return DossiersSQVF
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
     * @return DossiersSQVF
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
     * Set etapeCourante
     *
     * @param string $etapeCourante
     *
     * @return DossiersSQVF
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
     * Set dateDebutOperation
     *
     * @param \DateTime $dateDebutOperation
     *
     * @return DossiersSQVF
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
     * @return DossiersSQVF
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
     * @return DossiersSQVF
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
     * @return DossiersSQVF
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
     * Set archive
     *
     * @param integer $archive
     *
     * @return DossiersSQVF
     */
    public function setArchive($archive)
    {
        $this->archive = $archive;

        return $this;
    }

    /**
     * Get archive
     *
     * @return integer
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
     * @return DossiersSQVF
     */
    public function setNewUniqid($newUniqid)
    {
        $this->newUniqid = $newUniqid;

        return $this;
    }

    /**
     * Get newUniqid
     *
     * @return integer
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
     * @return DossiersSQVF
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
     * @return DossiersSQVF
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
     * Set montantPrincipal
     *
     * @param integer $montantPrincipal
     *
     * @return DossiersSQVF
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
     * @return DossiersSQVF
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
     * @return DossiersSQVF
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
