<?php

namespace DBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * userSQVF
 *
 * @ORM\Table(name="userSQVF")
 * @ORM\Entity(repositoryClass="DBundle\Repository\userSQVFRepository")
 */
class userSQVF
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
     * @ORM\Column(name="id_type_user", type="integer", nullable=true)
     */
    private $idTypeUser;

    /**
     * @var int
     *
     * @ORM\Column(name="id_centre_fiscal", type="integer", nullable=true)
     */
    private $idCentreFiscal;

    /**
     * @var int
     *
     * @ORM\Column(name="id_chef", type="integer", nullable=true)
     */
    private $idChef;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=200, nullable=true)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=200, nullable=true)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", length=400, nullable=true)
     */
    private $fullName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=1020, nullable=true)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=1020)
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="hash", type="string", length=200, nullable=true)
     */
    private $hash;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_activity", type="datetime", nullable=true)
     */
    private $lastActivity;

    /**
     * @var int
     *
     * @ORM\Column(name="archive", type="integer", nullable=true)
     */
    private $archive;

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
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set idTypeUser
     *
     * @param integer $idTypeUser
     *
     * @return userSQVF
     */
    public function setIdTypeUser($idTypeUser)
    {
        $this->idTypeUser = $idTypeUser;

        return $this;
    }

    /**
     * Get idTypeUser
     *
     * @return int
     */
    public function getIdTypeUser()
    {
        return $this->idTypeUser;
    }

    /**
     * Set idCentreFiscal
     *
     * @param integer $idCentreFiscal
     *
     * @return userSQVF
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
     * Set idChef
     *
     * @param integer $idChef
     *
     * @return userSQVF
     */
    public function setIdChef($idChef)
    {
        $this->idChef = $idChef;

        return $this;
    }

    /**
     * Get idChef
     *
     * @return int
     */
    public function getIdChef()
    {
        return $this->idChef;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return userSQVF
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return userSQVF
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set fullName
     *
     * @param string $fullName
     *
     * @return userSQVF
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * Get fullName
     *
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return userSQVF
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
     * Set password
     *
     * @param string $password
     *
     * @return userSQVF
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set hash
     *
     * @param string $hash
     *
     * @return userSQVF
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * Get hash
     *
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * Set lastActivity
     *
     * @param \DateTime $lastActivity
     *
     * @return userSQVF
     */
    public function setLastActivity($lastActivity)
    {
        $this->lastActivity = $lastActivity;

        return $this;
    }

    /**
     * Get lastActivity
     *
     * @return \DateTime
     */
    public function getLastActivity()
    {
        return $this->lastActivity;
    }

    /**
     * Set archive
     *
     * @param integer $archive
     *
     * @return userSQVF
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
     * Set createTime
     *
     * @param \DateTime $createTime
     *
     * @return userSQVF
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
     * @return userSQVF
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
}

