<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ExercicesVerifies
 *
 * @ORM\Table(name="ExercicesVerifies")
 * @ORM\Entity(repositoryClass="DBundle\Repository\ExercicesVerifiesRepository")
 */
class ExercicesVerifies
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
     * @var string
     *
     * @ORM\Column(name="uniqid", type="string", length=50, nullable=true)
     */
    private $uniqid;

    /**
     * @var string
     *
     * @ORM\Column(name="nif", type="string", length=50, nullable=true)
     */
    private $nif;

    /**
     * @var int
     *
     * @ORM\Column(name="annee_controle", type="integer", nullable=true)
     */
    private $anneeControle;

    /**
     * @var string
     *
     * @ORM\Column(name="type_controle", type="string", length=50, nullable=true)
     */
    private $typeControle;

    /**
     * @var int
     *
     * @ORM\Column(name="numero_notification_definitive", type="integer", nullable=true)
     */
    private $numeroNotificationDefinitive;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_notification_definitive", type="date", nullable=true)
     */
    private $dateNotificationDefinitive;

    /**
     * @var string
     *
     * @ORM\Column(name="type_impot", type="string", length=50, nullable=true)
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
     * @ORM\Column(name="etape_courante", type="string", length=50, nullable=true)
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
     * Set idDossier
     *
     * @param integer $idDossier
     *
     * @return ExercicesVerifies
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
     * @return ExercicesVerifies
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
     * @return ExercicesVerifies
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
     * @return ExercicesVerifies
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
     * @return ExercicesVerifies
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
     * @return ExercicesVerifies
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
     * @return ExercicesVerifies
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
     * @param \DateTime $dateNotificationDefinitive
     *
     * @return ExercicesVerifies
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
     * Set typeImpot
     *
     * @param string $typeImpot
     *
     * @return ExercicesVerifies
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
     * @return ExercicesVerifies
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
     * @return ExercicesVerifies
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
     * Set montantPrincipal
     *
     * @param integer $montantPrincipal
     *
     * @return ExercicesVerifies
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
     * @return ExercicesVerifies
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
     * @return ExercicesVerifies
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

