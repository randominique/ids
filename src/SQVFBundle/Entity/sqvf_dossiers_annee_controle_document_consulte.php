<?php

namespace SQVFBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * sqvf_dossiers_annee_controle_document_consulte
 *
 * @ORM\Table(name="sqvf_dossiers_annee_controle_document_consulte")
 * @ORM\Entity(repositoryClass="SQVFBundle\Repository\sqvf_dossiers_annee_controle_document_consulteRepository")
 */
class sqvf_dossiers_annee_controle_document_consulte
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
     * @var int
     *
     * @ORM\Column(name="id_document_consulte", type="integer", nullable=true)
     */
    private $idDocumentConsulte;

    /**
     * @var int
     *
     * @ORM\Column(name="is_tmp", type="integer", nullable=true)
     */
    private $isTmp;


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
     * @return sqvf_dossiers_annee_controle_document_consulte
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
     * @return sqvf_dossiers_annee_controle_document_consulte
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
     * @return sqvf_dossiers_annee_controle_document_consulte
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
     * Set idDocumentConsulte
     *
     * @param integer $idDocumentConsulte
     *
     * @return sqvf_dossiers_annee_controle_document_consulte
     */
    public function setIdDocumentConsulte($idDocumentConsulte)
    {
        $this->idDocumentConsulte = $idDocumentConsulte;

        return $this;
    }

    /**
     * Get idDocumentConsulte
     *
     * @return int
     */
    public function getIdDocumentConsulte()
    {
        return $this->idDocumentConsulte;
    }

    /**
     * Set isTmp
     *
     * @param integer $isTmp
     *
     * @return sqvf_dossiers_annee_controle_document_consulte
     */
    public function setIsTmp($isTmp)
    {
        $this->isTmp = $isTmp;

        return $this;
    }

    /**
     * Get isTmp
     *
     * @return int
     */
    public function getIsTmp()
    {
        return $this->isTmp;
    }
}

