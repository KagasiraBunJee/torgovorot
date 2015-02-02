<?php

namespace Torgovorot\TorgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdsResume
 */
class AdsResume
{
    /**
     * @var integer
     */
    private $ownerId;

    /**
     * @var string
     */
    private $fio;

    /**
     * @var string
     */
    private $position;

    /**
     * @var \DateTime
     */
    private $birthDate;

    /**
     * @var string
     */
    private $sex;

    /**
     * @var string
     */
    private $family;

    /**
     * @var integer
     */
    private $driver;

    /**
     * @var integer
     */
    private $children;

    /**
     * @var string
     */
    private $jobsIds;

    /**
     * @var string
     */
    private $timetable;

    /**
     * @var string
     */
    private $education;

    /**
     * @var \DateTime
     */
    private $startStudy;

    /**
     * @var \DateTime
     */
    private $endStudy;

    /**
     * @var integer
     */
    private $isStudy;

    /**
     * @var string
     */
    private $skills;

    /**
     * @var string
     */
    private $aboutMe;

    /**
     * @var string
     */
    private $contacts;

    /**
     * @var \DateTime
     */
    private $createTime;

    /**
     * @var integer
     */
    private $days;

    /**
     * @var integer
     */
    protected $id;


    /**
     * Set ownerId
     *
     * @param integer $ownerId
     * @return AdsResume
     */
    public function setOwnerId($ownerId)
    {
        $this->ownerId = $ownerId;

        return $this;
    }

    /**
     * Get ownerId
     *
     * @return integer 
     */
    public function getOwnerId()
    {
        return $this->ownerId;
    }

    /**
     * Set fio
     *
     * @param string $fio
     * @return AdsResume
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
     * Set position
     *
     * @param string $position
     * @return AdsResume
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set birthDate
     *
     * @param \DateTime $birthDate
     * @return AdsResume
     */
    public function setBirthDate($birthDate)
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    /**
     * Get birthDate
     *
     * @return \DateTime 
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * Set sex
     *
     * @param string $sex
     * @return AdsResume
     */
    public function setSex($sex)
    {
        $this->sex = $sex;

        return $this;
    }

    /**
     * Get sex
     *
     * @return string 
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set family
     *
     * @param string $family
     * @return AdsResume
     */
    public function setFamily($family)
    {
        $this->family = $family;

        return $this;
    }

    /**
     * Get family
     *
     * @return string 
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * Set driver
     *
     * @param integer $driver
     * @return AdsResume
     */
    public function setDriver($driver)
    {
        $this->driver = $driver;

        return $this;
    }

    /**
     * Get driver
     *
     * @return integer 
     */
    public function getDriver()
    {
        return $this->driver;
    }

    /**
     * Set children
     *
     * @param integer $children
     * @return AdsResume
     */
    public function setChildren($children)
    {
        $this->children = $children;

        return $this;
    }

    /**
     * Get children
     *
     * @return integer 
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set jobsIds
     *
     * @param string $jobsIds
     * @return AdsResume
     */
    public function setJobsIds($jobsIds)
    {
        $this->jobsIds = $jobsIds;

        return $this;
    }

    /**
     * Get jobsIds
     *
     * @return string 
     */
    public function getJobsIds()
    {
        return $this->jobsIds;
    }

    /**
     * Set timetable
     *
     * @param string $timetable
     * @return AdsResume
     */
    public function setTimetable($timetable)
    {
        $this->timetable = $timetable;

        return $this;
    }

    /**
     * Get timetable
     *
     * @return string 
     */
    public function getTimetable()
    {
        return $this->timetable;
    }

    /**
     * Set education
     *
     * @param string $education
     * @return AdsResume
     */
    public function setEducation($education)
    {
        $this->education = $education;

        return $this;
    }

    /**
     * Get education
     *
     * @return string 
     */
    public function getEducation()
    {
        return $this->education;
    }

    /**
     * Set startStudy
     *
     * @param \DateTime $startStudy
     * @return AdsResume
     */
    public function setStartStudy($startStudy)
    {
        $this->startStudy = $startStudy;

        return $this;
    }

    /**
     * Get startStudy
     *
     * @return \DateTime 
     */
    public function getStartStudy()
    {
        return $this->startStudy;
    }

    /**
     * Set endStudy
     *
     * @param \DateTime $endStudy
     * @return AdsResume
     */
    public function setEndStudy($endStudy)
    {
        $this->endStudy = $endStudy;

        return $this;
    }

    /**
     * Get endStudy
     *
     * @return \DateTime 
     */
    public function getEndStudy()
    {
        return $this->endStudy;
    }

    /**
     * Set isStudy
     *
     * @param integer $isStudy
     * @return AdsResume
     */
    public function setIsStudy($isStudy)
    {
        $this->isStudy = $isStudy;

        return $this;
    }

    /**
     * Get isStudy
     *
     * @return integer 
     */
    public function getIsStudy()
    {
        return $this->isStudy;
    }

    /**
     * Set skills
     *
     * @param string $skills
     * @return AdsResume
     */
    public function setSkills($skills)
    {
        $this->skills = $skills;

        return $this;
    }

    /**
     * Get skills
     *
     * @return string 
     */
    public function getSkills()
    {
        return $this->skills;
    }

    /**
     * Set aboutMe
     *
     * @param string $aboutMe
     * @return AdsResume
     */
    public function setAboutMe($aboutMe)
    {
        $this->aboutMe = $aboutMe;

        return $this;
    }

    /**
     * Get aboutMe
     *
     * @return string 
     */
    public function getAboutMe()
    {
        return $this->aboutMe;
    }

    /**
     * Set contacts
     *
     * @param string $contacts
     * @return AdsResume
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
     * Set createTime
     *
     * @param \DateTime $createTime
     * @return AdsResume
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
     * Set days
     *
     * @param integer $days
     * @return AdsResume
     */
    public function setDays($days)
    {
        $this->days = $days;

        return $this;
    }

    /**
     * Get days
     *
     * @return integer 
     */
    public function getDays()
    {
        return $this->days;
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
    /**
     * @var integer
     */
    private $experience;


    /**
     * Set experience
     *
     * @param integer $experience
     * @return AdsResume
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;

        return $this;
    }

    /**
     * Get experience
     *
     * @return integer 
     */
    public function getExperience()
    {
        return $this->experience;
    }
    /**
     * @var \DateTime
     */
    private $time;


    /**
     * Set time
     *
     * @param \DateTime $time
     * @return AdsResume
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
    }
    /**
     * @var integer
     */
    private $emergency;

    /**
     * @var integer
     */
    private $premium;

    /**
     * @var integer
     */
    private $vip;


    /**
     * Set emergency
     *
     * @param integer $emergency
     * @return AdsResume
     */
    public function setEmergency($emergency)
    {
        $this->emergency = $emergency;

        return $this;
    }

    /**
     * Get emergency
     *
     * @return integer 
     */
    public function getEmergency()
    {
        return $this->emergency;
    }

    /**
     * Set premium
     *
     * @param integer $premium
     * @return AdsResume
     */
    public function setPremium($premium)
    {
        $this->premium = $premium;

        return $this;
    }

    /**
     * Get premium
     *
     * @return integer 
     */
    public function getPremium()
    {
        return $this->premium;
    }

    /**
     * Set vip
     *
     * @param integer $vip
     * @return AdsResume
     */
    public function setVip($vip)
    {
        $this->vip = $vip;

        return $this;
    }

    /**
     * Get vip
     *
     * @return integer 
     */
    public function getVip()
    {
        return $this->vip;
    }
    /**
     * @var integer
     */
    private $views;


    /**
     * Set views
     *
     * @param integer $views
     * @return AdsResume
     */
    public function setViews($views)
    {
        $this->views = $views;

        return $this;
    }

    /**
     * Get views
     *
     * @return integer 
     */
    public function getViews()
    {
        return $this->views;
    }
    /**
     * @var integer
     */
    private $special;


    /**
     * Set special
     *
     * @param integer $special
     * @return AdsResume
     */
    public function setSpecial($special)
    {
        $this->special = $special;

        return $this;
    }

    /**
     * Get special
     *
     * @return integer 
     */
    public function getSpecial()
    {
        return $this->special;
    }
    /**
     * @var integer
     */
    private $adsState;


    /**
     * Set adsState
     *
     * @param integer $adsState
     * @return AdsResume
     */
    public function setAdsState($adsState)
    {
        $this->adsState = $adsState;

        return $this;
    }

    /**
     * Get adsState
     *
     * @return integer 
     */
    public function getAdsState()
    {
        return $this->adsState;
    }
    /**
     * @var string
     */
    private $town;


    /**
     * Set town
     *
     * @param string $town
     * @return AdsResume
     */
    public function setTown($town)
    {
        $this->town = $town;

        return $this;
    }

    /**
     * Get town
     *
     * @return string 
     */
    public function getTown()
    {
        return $this->town;
    }
    /**
     * @var string
     */
    private $addrId;


    /**
     * Set addrId
     *
     * @param string $addrId
     * @return AdsResume
     */
    public function setAddrId($addrId)
    {
        $this->addrId = $addrId;

        return $this;
    }

    /**
     * Get addrId
     *
     * @return string 
     */
    public function getAddrId()
    {
        return $this->addrId;
    }
}
