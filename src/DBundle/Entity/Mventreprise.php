<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mventreprise
 *
 * @ORM\Table(name="mventreprise")
 * @ORM\Entity(repositoryClass="DBundle\Repository\MventrepriseRepository")
 */
class Mventreprise
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
     * @var string
     *
     * @ORM\Column(name="MATRIMOINE_NAME", type="string", length=255)
     */
    private $registname;

    /**
     * @var string
     *
     * @ORM\Column(name="TYPE_ENTREPRISE", type="string", length=255)
     */
    private $cgdesignation;

    /**
     * @var string
     *
     * @ORM\Column(name="RAISON_SOCIALE", type="string", length=255)
     */
    private $rs;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM_COMMERCIAL", type="string", length=255, nullable=true)
     */
    private $nomcommercial;

    /**
     * @var string
     *
     * @ORM\Column(name="NUM_STAT", type="string", length=255)
     */
    private $impots;

    /**
     * @var string
     *
     * @ORM\Column(name="NOM_INTERLOCUTEUR", type="string", length=255)
     */
    private $nom_dirigeant;

    /**
     * @var string
     *
     * @ORM\Column(name="ANCIEN_NIF", type="string", length=255)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="MAIL_REPRESENTANT", type="string", length=255)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="TITRE_INTERLOCUTEUR", type="string", length=255)
     */
    private $nom_qualite_contact;

    /**
     * @var string
     *
     * @ORM\Column(name="TEL_REPRESENTANT", type="string", length=255)
     */
    private $contact_phone;

    /**
     * @var string
     *
     * @ORM\Column(name="ADRESSE_INTERLOCUTEUR", type="string", length=255)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="PROPRIETAIRE", type="string", length=255)
     */
    private $proprietaire;

    /**
     * Get id
     *
     * @return integer
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
     * @return Mventreprise
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
     * Set registname
     *
     * @param string $registname
     *
     * @return Mventreprise
     */
    public function setRegistname($registname)
    {
        $this->registname = $registname;

        return $this;
    }

    /**
     * Get registname
     *
     * @return string
     */
    public function getRegistname()
    {
        return $this->registname;
    }

    /**
     * Set cgdesignation
     *
     * @param string $cgdesignation
     *
     * @return Mventreprise
     */
    public function setCgdesignation($cgdesignation)
    {
        $this->cgdesignation = $cgdesignation;

        return $this;
    }

    /**
     * Get cgdesignation
     *
     * @return string
     */
    public function getCgdesignation()
    {
        return $this->cgdesignation;
    }

    /**
     * Set rs
     *
     * @param string $rs
     *
     * @return Mventreprise
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
     * Set nomcommercial
     *
     * @param string $nomcommercial
     *
     * @return Mventreprise
     */
    public function setNomcommercial($nomcommercial)
    {
        $this->nomcommercial = $nomcommercial;

        return $this;
    }

    /**
     * Get nomcommercial
     *
     * @return string
     */
    public function getNomcommercial()
    {
        return $this->nomcommercial;
    }

    /**
     * Set impots
     *
     * @param string $impots
     *
     * @return Mventreprise
     */
    public function setImpots($impots)
    {
        $this->impots = $impots;

        return $this;
    }

    /**
     * Get impots
     *
     * @return string
     */
    public function getImpots()
    {
        return $this->impots;
    }

    /**
     * Set nomDirigeant
     *
     * @param string $nomDirigeant
     *
     * @return Mventreprise
     */
    public function setNomDirigeant($nomDirigeant)
    {
        $this->nom_dirigeant = $nomDirigeant;

        return $this;
    }

    /**
     * Get nomDirigeant
     *
     * @return string
     */
    public function getNomDirigeant()
    {
        return $this->nom_dirigeant;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Mventreprise
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Mventreprise
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set nomQualiteContact
     *
     * @param string $nomQualiteContact
     *
     * @return Mventreprise
     */
    public function setNomQualiteContact($nomQualiteContact)
    {
        $this->nom_qualite_contact = $nomQualiteContact;

        return $this;
    }

    /**
     * Get nomQualiteContact
     *
     * @return string
     */
    public function getNomQualiteContact()
    {
        return $this->nom_qualite_contact;
    }

    /**
     * Set contactPhone
     *
     * @param string $contactPhone
     *
     * @return Mventreprise
     */
    public function setContactPhone($contactPhone)
    {
        $this->contact_phone = $contactPhone;

        return $this;
    }

    /**
     * Get contactPhone
     *
     * @return string
     */
    public function getContactPhone()
    {
        return $this->contact_phone;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Mventreprise
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
     * Set proprietaire
     *
     * @param string $proprietaire
     *
     * @return Mventreprise
     */
    public function setProprietaire($proprietaire)
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    /**
     * Get proprietaire
     *
     * @return string
     */
    public function getProprietaire()
    {
        return $this->proprietaire;
    }
}
