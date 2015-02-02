<?php

namespace Torgovorot\TorgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RealtyType
 */
class RealtyType
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $id;

    
    /**
     * @var integer
     */
    private $count;    

    /**
     * Set name
     *
     * @param string $name
     * @return RealtyType
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Set count
     *
     * @param int $count
     * @return RealtyType
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * Get count
     *
     * @return int 
     */
    public function getCount()
    {
        return $this->count;
    }    
    
}
