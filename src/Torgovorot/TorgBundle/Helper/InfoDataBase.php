<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Torgovorot\TorgBundle\Helper;


use Torgovorot\TorgBundle\Entity\BathType;
use Torgovorot\TorgBundle\Entity\CommType;
use Torgovorot\TorgBundle\Entity\GarageType;
use Torgovorot\TorgBundle\Entity\HouseMaterial;
use Torgovorot\TorgBundle\Entity\HousePlaning;
use Torgovorot\TorgBundle\Entity\HouseType;
use Torgovorot\TorgBundle\Entity\Timetable;
use Torgovorot\TorgBundle\Entity\Vacancies;
use Torgovorot\TorgBundle\Entity\Convinience;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
/**
 * Description of InfoDataBase
 *
 * @author serg
 */
class InfoDataBase {
    
    private $em;
    public function __construct(EntityManager $em1)
    {
        
        $this->em = $em1;
    }
    
    public function getBathNames($array)
    {
        $bath_object = new BathType();
        //$bath_object = $this->em->getDoctrine()->getRepository('TorgovorotTorgBundle:BathType')->findAll();
        
        $explode = explode(";", $array);
        
        $arr_name = array();
        if(count($explode) > 1)
        {
            foreach($explode as $value)
            {
                $bath_object = $this->em->getRepository('TorgovorotTorgBundle:BathType')->findBy(array('id' => $value));
                
                if($bath_object)
                {
                    $arr_name[] = $bath_object;
                }
            
            }
            
            return $arr_name;
        }
        else 
        {
            $bath_object = $this->em->getRepository('TorgovorotTorgBundle:BathType')->findOneById($explode[0]);
            $name = $bath_object->getName();
            return $name;
        }
        
    }
    
    public function getCommTypeNames($array)
    {
        $object = new CommType();
        
        $explode = explode(";", $array);
        
        $arr_name = array();
        
        if(count($explode) > 1)
        {
            foreach($explode as $value)
            {
                $object = $this->em->getRepository('TorgovorotTorgBundle:BathType')->findOneById($value);
                
                if($object)
                {
                    $arr_name[] = $object;
                }
            }
        }
        else
        {
            $object = $this->em->getRepository('TorgovorotTorgBundle:BathType')->findOneById($explode[0]);
            
            return $object->getName();
        }
    }
    
    public function getGarageNames($array)
    {
        $object = new GarageType();
        
        $explode = explode(";", $array);
        
        $arr_name = array();
        
        if(count($explode) > 1)
        {
            foreach($explode as $value)
            {
                $object = $this->em->getRepository('TorgovorotTorgBundle:GarageType')->findOneById($value);
                
                if($object)
                {
                    $arr_name[] = $object;
                }
            }
        }
        else
        {
            $object = $this->em->getRepository('TorgovorotTorgBundle:GarageType')->findOneById($explode[0]);
            
            return $object->getName();
        }
    }
    
    public function getHouseMaterialNames($array)
    {
        $object = new HouseMaterial();
        
        $explode = explode(";", $array);
        
        $arr_name = array();
        
        if(count($explode) > 1)
        {
            foreach($explode as $value)
            {
                $object = $this->em->getRepository('TorgovorotTorgBundle:HouseMaterial')->findOneById($value);
                if($object)
                {
                    $arr_name[] = $object;
                }
            }
        }
        else
        {
            $object = $this->em->getRepository('TorgovorotTorgBundle:HouseMaterial')->findOneById($explode[0]);
            return $object->getName();
        }
    }
    
    public function getHousePlaningNames($array)
    {
        $object = new HousePlaning();
        
        $explode = explode(";", $array);
        
        $arr_name = array();
        
        if(count($explode) > 1)
        {
            foreach($explode as $value)
            {
                $object = $this->em->getRepository('TorgovorotTorgBundle:HousePlaning')->findOneById($value);
                if($object)
                {
                    $arr_name[] = $object;
                }
            }
        }
        else
        {
            $object = $this->em->getRepository('TorgovorotTorgBundle:HousePlaning')->findOneById($explode[0]);
            return $object->getName();
        }
    }
    
    public function getHouseTypeNames($array)
    {
        $object = new HouseType();
        
        $explode = explode(";", $array);
        
        $arr_name = array();
        
        if(count($explode) > 1)
        {
            foreach($explode as $value)
            {
                $object = $this->em->getRepository('TorgovorotTorgBundle:HouseType')->findOneById($value);
                if($object)
                {
                    $arr_name[] = $object;
                }
            }
        }
        else
        {
            $object = $this->em->getRepository('TorgovorotTorgBundle:HouseType')->findOneById($explode[0]);
            return $object->getName();
        }
    }
    
    public function getTimetableNames($array)
    {
        $object = new Timetable();
        
        $explode = explode(";", $array);
        
        $arr_name = array();
        
        if(count($explode) > 1)
        {
            foreach($explode as $value)
            {
                $object = $this->em->getRepository('TorgovorotTorgBundle:Timetable')->findOneById($value);
                if($object)
                {
                    $arr_name[] = $object;
                }
            }
        }
        else
        {
            $object = $this->em->getRepository('TorgovorotTorgBundle:HouseType')->findOneById($explode[0]);
            return $object->getName();
        }
    }
    
    public function getVacanciesNames($array)
    {
        $object = new Vacancies();
        
        $explode = explode(";", $array);
        
        $arr_name = array();
        
        if(count($explode) > 1)
        {
            foreach($explode as $value)
            {
                
                $object = $this->em->getRepository('TorgovorotTorgBundle:Vacancies')->findOneById($value);
                if($object)
                {
                    $arr_name[] = $object;
                }
            }
        }
        else
        {
            $object = $this->em->getRepository('TorgovorotTorgBundle:Vacancies')->findOneById($explode[0]);
            return $object->getName();
        }
    }
    
    public function getConvinientName($ch_id, $type)
    {
        $conv_object = new Convinience();
        $conv_object = $this->em->getRepository('TorgovorotTorgBundle:Convinience')->findOneBy(array('chId'=>$ch_id,'typeId'=>$type));
        
        return $conv_object->getName();
    }
    
    private function getList($offset, $items, $limit)
    {
        $start = ($offset-1)*$limit;
        if($offset == 1)
        {
            $start = 0;
        }
        
        $end = $start + $limit;
        
        foreach($items as $key => $value)
        {
            if($key >= $start and $key < $end){}
                
            else unset($items[$key]);
        }
        
        return $items;
    }
    
}
