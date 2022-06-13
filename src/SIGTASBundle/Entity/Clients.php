<?php

namespace SIGTASBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Clients
 *
 * @ORM\Table(name="tax_payer")
 * @ORM\Entity(repositoryClass="SIGTASBundle\Repository\ClientsRepository")
 */
class Clients
{
    /**
     * @var int
     *
     * @ORM\Column(name="TAX_PAYER_NO", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="FISCAL_NO", type="string", length=255)
     */
    private $nif;

    /**
     * @var string
     *
     * @ORM\Column(name="MAILING_ADDRESS", type="string", length=255)
     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="KEBELE_DESC", type="string", length=255)
     */
    private $ville;

    /**
     * @var string
     *
     * @ORM\Column(name="FISCAL_REGIME_NO", type="string", length=255)
     */
    private $regime_fiscal;

    
    /**
     * @var datetime
     *
     * @ORM\Column(name="INACTIF_DATE", type="datetime")
     */
    private $inactifDate;

    private $sigtas; 

    private $sigtasRs;

    private $sigtasNc;

    private $sigtasMail;

    private $secteurActivite;

    private $sigtasPhone;

    private $rs;


    /**
     * Set rs
     */
    public function setRs($rs)
    {
        $this->rs = $rs;

        return $this;
    }
    /**
     * Get rs
     */
    public function getRs()
    {
        return $this->rs;
    }


    /**
     * Set sigtas
     */
    public function setSigtas($sigtas)
    {
        $this->sigtas = $sigtas;

        return $this;
    }
    /**
     * Get sigtas
     */
    public function getSigtas()
    {
        return $this->sigtas;
    }
    /**
     * Set inactifdate
     */
    public function setInactifDate($inactifDate)
    {
        $this->inactifDate = $inactifDate;

        return $this;
    }
    /**
     * Get inactifdate
     */
    public function getInactifDate()
    {
        return $this->inactifDate;
    }

    /**
     * Set SecteurActivite
     */
    public function setSecteurActivite($secteurActivite)
    {
        $this->secteurActivite = $secteurActivite;

        return $this;
    }
    /**
     * Get SecteurActivite
     */
    public function getSecteurActivite()
    {
        return $this->secteurActivite;
    }

    /**
     * Set sigtasRs
     */
    public function setSigtasRs($sigtasRs)
    {
        $this->sigtasRs = $sigtasRs;

        return $this;
    }
    /**
     * Get sigtasRs
     */
    public function getSigtasRs()
    {
        return $this->sigtasRs;
    }
    /**
     * Set sigtasNc
     */
    public function setSigtasNc($sigtasNc)
    {
        $this->sigtasNc = $sigtasNc;

        return $this;
    }
    /**
     * Get sigtasNc
     */
    public function getSigtasNc()
    {
        return $this->sigtasNc;
    }
     /**
     * Set sigtasMail
     */
    public function setSigtasMail($sigtasMail)
    {
        $this->sigtasMail = $sigtasMail;

        return $this;
    }
    /**
     * Get sigtasMail
     */
    public function getSigtasMail()
    {
        return $this->sigtasMail;
    }

    /**
     * Set sigtasPhone
     */
    public function setSigtasPhone($sigtasPhone)
    {
        $this->sigtasPhone = $sigtasPhone;

        return $this;
    }

    /**
     * Get sigtasPhone
     */
    public function getSigtasPhone()
    {
        return $this->sigtasPhone;
    }


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
     * Set adresse
     *
     * @param string $adresse
     *
     * @return Clients
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
     * Set ville
     *
     * @param string $ville
     *
     * @return Clients
     */
    public function setVille($ville)
    {
        $this->ville = $ville;

        return $this;
    }

    /**
     * Get ville
     *
     * @return string
     */
    public function getVille()
    {
        return $this->ville;
    }

    /**
     * Set regimeFiscal
     *
     * @param string $regimeFiscal
     *
     * @return Clients
     */
    public function setRegimeFiscal($regimeFiscal)
    {
        $this->regime_fiscal = $regimeFiscal;

        return $this;
    }

    /**
     * Get regimeFiscal
     *
     * @return string
     */
    public function getRegimeFiscal()
    {
        return $this->regime_fiscal;
    }

    /**
     * Set nif
     *
     * @param string $nif
     *
     * @return Clients
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
}
