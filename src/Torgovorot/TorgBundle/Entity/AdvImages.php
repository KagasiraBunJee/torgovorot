<?php

namespace Torgovorot\TorgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AdvImages
 */
class AdvImages
{
    /**
     * @var integer
     */
    private $ownerId;

    /**
     * @var string
     */
    private $iName;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set ownerId
     *
     * @param integer $ownerId
     * @return AdvImages
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
     * Set iName
     *
     * @param string $iName
     * @return AdvImages
     */
    public function setIName($iName)
    {
        $this->iName = $iName;

        return $this;
    }

    /**
     * Get iName
     *
     * @return string 
     */
    public function getIName()
    {
        return $this->iName;
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
