<?php

namespace SQVFBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DocumentsFichiers
 *
 * @ORM\Table(name="sqvf_documents_fichiers")
 * @ORM\Entity(repositoryClass="SQVFBundle\Repository\sqvf_documents_fichiersRepository")
 */
class sqvf_documents_fichiers
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
     * @ORM\Column(name="id_document", type="integer", nullable=true)
     */
    private $idDocument;

    /**
     * @var string
     *
     * @ORM\Column(name="extension", type="string", length=5, nullable=true)
     */
    private $extension;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=150, nullable=true)
     */
    private $filename;

    /**
     * @var int
     *
     * @ORM\Column(name="active", type="integer", nullable=true)
     */
    private $active;


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
     * Set idDocument
     *
     * @param integer $idDocument
     *
     * @return DocumentsFichiers
     */
    public function setIdDocument($idDocument)
    {
        $this->idDocument = $idDocument;

        return $this;
    }

    /**
     * Get idDocument
     *
     * @return int
     */
    public function getIdDocument()
    {
        return $this->idDocument;
    }

    /**
     * Set extension
     *
     * @param string $extension
     *
     * @return DocumentsFichiers
     */
    public function setExtension($extension)
    {
        $this->extension = $extension;

        return $this;
    }

    /**
     * Get extension
     *
     * @return string
     */
    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Set filename
     *
     * @param string $filename
     *
     * @return DocumentsFichiers
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set active
     *
     * @param integer $active
     *
     * @return DocumentsFichiers
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return int
     */
    public function getActive()
    {
        return $this->active;
    }
}

