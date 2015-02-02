<?php

namespace Torgovorot\TorgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Views
 */
class Views
{
    /**
     * @var string
     */
    private $ip;

    /**
     * @var \DateTime
     */
    private $time;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set ip
     *
     * @param string $ip
     * @return Views
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string 
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     * @return Views
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
