<?php

namespace Torgovorot\TorgBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Torgovorot\TorgBundle\Entity\Cars;
/**
 * CarsMark
 */
class CarsMark
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    
    private $carId;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set name
     *
     * @param string $name
     * @return CarsMark
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
     * Set carId
     *
     * @param integer $carId
     * @return CarsMark
     */
    public function setCarId(Cars $carId)
    {
        $this->carId = $carId->getId();

        return $this;
    }

    /**
     * Get carId
     *
     * @return integer 
     */
    public function getCarId()
    {
        return $this->carId;
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
