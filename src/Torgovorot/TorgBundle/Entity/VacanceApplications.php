<?php

namespace Torgovorot\TorgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * VacanceApplications
 */
class VacanceApplications
{
    /**
     * @var integer
     */
    private $vacanceId;

    /**
     * @var integer
     */
    private $resumeId;

    /**
     * @var \DateTime
     */
    private $time;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set vacanceId
     *
     * @param integer $vacanceId
     * @return VacanceApplications
     */
    public function setVacanceId($vacanceId)
    {
        $this->vacanceId = $vacanceId;

        return $this;
    }

    /**
     * Get vacanceId
     *
     * @return integer 
     */
    public function getVacanceId()
    {
        return $this->vacanceId;
    }

    /**
     * Set resumeId
     *
     * @param integer $resumeId
     * @return VacanceApplications
     */
    public function setResumeId($resumeId)
    {
        $this->resumeId = $resumeId;

        return $this;
    }

    /**
     * Get resumeId
     *
     * @return integer 
     */
    public function getResumeId()
    {
        return $this->resumeId;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     * @return VacanceApplications
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
