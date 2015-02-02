<?php

namespace Torgovorot\TorgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Menu
 */
class Menu
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $cpuName;

    /**
     * @var string
     */
    private $link;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set name
     *
     * @param string $name
     * @return Menu
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set cpuName
     *
     * @param string $cpuName
     * @return Menu
     */
    public function setCpuName($cpuName)
    {
        $this->cpuName = $cpuName;

        return $this;
    }

    /**
     * Get cpuName
     *
     * @return string 
     */
    public function getCpuName()
    {
        return $this->cpuName;
    }

    /**
     * Set link
     *
     * @param string $link
     * @return Menu
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string 
     */
    public function getLink()
    {
        return $this->link;
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
