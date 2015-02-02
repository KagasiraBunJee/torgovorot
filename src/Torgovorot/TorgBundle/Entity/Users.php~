<?php

namespace Torgovorot\TorgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
/**
 * Users
 */
/**
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Torgovorot\TorgBundle\Entity\UserRepository")
 */
class Users implements UserInterface
{
    /**
     * @var string
     */
    private $userName;

    /**
     * @var string
     */
    private $passNorm;
    
    
    /**
     * @var string
     */
    private $passHex;

    /**
     * @var string
     */
    private $email;

    /**
     * @var integer
     */
    private $accessLvl;

    /**
     * @var \DateTime
     */
    private $registerTime;

    /**
     * @var \DateTime
     */
    private $lastActive;

    /**
     * @var integer
     */
    private $online;

    /**
     * @var string
     */
    private $salt;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set userName
     *
     * @param string $userName
     * @return Users
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get userName
     *
     * @return string 
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set passNorm
     *
     * @param string $passNorm
     * @return Users
     */
    public function setPassNorm($passNorm)
    {
        $this->passNorm = $passNorm;

        return $this;
    }

    /**
     * Get passNorm
     *
     * @return string 
     */
    public function getPassNorm()
    {
        return $this->passNorm;
    }

    /**
     * Set passHex
     *
     * @param string $passHex
     * @return Users
     */
    public function setPassHex($passHex)
    {
        $this->passHex = $passHex;

        return $this;
    }

    /**
     * Get passHex
     *
     * @return string 
     */
    public function getPassHex()
    {
        return $this->passHex;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Users
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
     * Set accessLvl
     *
     * @param integer $accessLvl
     * @return Users
     */
    public function setAccessLvl($accessLvl)
    {
        $this->accessLvl = $accessLvl;

        return $this;
    }

    /**
     * Get accessLvl
     *
     * @return integer 
     */
    public function getAccessLvl()
    {
        return $this->accessLvl;
    }

    /**
     * Set registerTime
     *
     * @param \DateTime $registerTime
     * @return Users
     */
    public function setRegisterTime($registerTime)
    {
        $this->registerTime = $registerTime;

        return $this;
    }

    /**
     * Get registerTime
     *
     * @return \DateTime 
     */
    public function getRegisterTime()
    {
        return $this->registerTime;
    }

    /**
     * Set lastActive
     *
     * @param \DateTime $lastActive
     * @return Users
     */
    public function setLastActive($lastActive)
    {
        $this->lastActive = $lastActive;

        return $this;
    }

    /**
     * Get lastActive
     *
     * @return \DateTime 
     */
    public function getLastActive()
    {
        return $this->lastActive;
    }

    /**
     * Set online
     *
     * @param integer $online
     * @return Users
     */
    public function setOnline($online)
    {
        $this->online = $online;

        return $this;
    }

    /**
     * Get online
     *
     * @return integer 
     */
    public function getOnline()
    {
        return $this->online;
    }

    /**
     * Set salt
     *
     * @param string $salt
     * @return Users
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Get salt
     *
     * @return string 
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function eraseCredentials() {
        
    }

    public function getPassword() {
        return $this->passHex;
    }

    public function getRoles() {
        
        if($this->rolesStr == "ROLE_ADMIN")
        {
            return array('ROLE_ADMIN');
        }
        else
        {
            return array('ROLE_USER');
        }
        
    }

    /**
     * @var integer
     */
    private $addrId;

    /**
     * @var string
     */
    private $photo;

    /**
     * @var string
     */
    private $about;


    /**
     * Set addrId
     *
     * @param String $addrId
     * @return Users
     */
    public function setAddrId($addrId)
    {
        $this->addrId = $addrId;

        return $this;
    }

    /**
     * Get addrId
     *
     * @return integer 
     */
    public function getAddrId()
    {
        return $this->addrId;
    }

    /**
     * Set photo
     *
     * @param string $photo
     * @return Users
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string 
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set about
     *
     * @param string $about
     * @return Users
     */
    public function setAbout($about)
    {
        $this->about = $about;

        return $this;
    }

    /**
     * Get about
     *
     * @return string 
     */
    public function getAbout()
    {
        return $this->about;
    }
    /**
     * @var string
     */
    private $contacts;


    /**
     * Set contacts
     *
     * @param string $contacts
     * @return Users
     */
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;

        return $this;
    }

    /**
     * Get contacts
     *
     * @return string 
     */
    public function getContacts()
    {
        return $this->contacts;
    }
    /**
     * @var string
     */
    private $fio;


    /**
     * Set fio
     *
     * @param string $fio
     * @return Users
     */
    public function setFio($fio)
    {
        $this->fio = $fio;

        return $this;
    }

    /**
     * Get fio
     *
     * @return string 
     */
    public function getFio()
    {
        return $this->fio;
    }
    /**
     * @var string
     */
    private $tel;

    /**
     * @var string
     */
    private $fax;

    /**
     * @var string
     */
    private $mobile;


    /**
     * Set tel
     *
     * @param string $tel
     * @return Users
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string 
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set fax
     *
     * @param string $fax
     * @return Users
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string 
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     * @return Users
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string 
     */
    public function getMobile()
    {
        return $this->mobile;
    }
    /**
     * @var string
     */
    private $discounts;

    /**
     * @var string
     */
    private $rolesStr;


    /**
     * Set discounts
     *
     * @param string $discounts
     * @return Users
     */
    public function setDiscounts($discounts)
    {
        $this->discounts = $discounts;

        return $this;
    }

    /**
     * Get discounts
     *
     * @return string 
     */
    public function getDiscounts()
    {
        return $this->discounts;
    }

    /**
     * Set rolesStr
     *
     * @param string $rolesStr
     * @return Users
     */
    public function setRolesStr($rolesStr)
    {
        $this->rolesStr = $rolesStr;

        return $this;
    }

    /**
     * Get rolesStr
     *
     * @return string 
     */
    public function getRolesStr()
    {
        return $this->rolesStr;
    }
    /**
     * @var string
     */
    private $companyName;


    /**
     * Set companyName
     *
     * @param string $companyName
     * @return Users
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return string 
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }
    /**
     * @var integer
     */
    private $credits;


    /**
     * Set credits
     *
     * @param integer $credits
     * @return Users
     */
    public function setCredits($credits)
    {
        $this->credits = $credits;

        return $this;
    }

    /**
     * Get credits
     *
     * @return integer 
     */
    public function getCredits()
    {
        return $this->credits;
    }
}
