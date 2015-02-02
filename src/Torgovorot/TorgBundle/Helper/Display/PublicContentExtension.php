<?php

# Torgovorot/TorgBundle/Helper/Display/PublicContentExtension.php

namespace Torgovorot\TorgBundle\Helper\Display;

use Symfony\Component\HttpKernel\KernelInterface;
use Doctrine\ORM\EntityManager;
//use Symfony\Bundle\TwigBundle\Debug\TimedTwigEngine;
use Torgovorot\TorgBundle\Entity\AdsRealty;
use Torgovorot\TorgBundle\Entity\AdsVacance;
use Torgovorot\TorgBundle\Entity\AdsResume;
use Torgovorot\TorgBundle\Entity\AdsGoods;
use Torgovorot\TorgBundle\Entity\AdsCars;
use Torgovorot\TorgBundle\Entity\AdsEvents;
use Torgovorot\TorgBundle\Entity\Cats;
use Torgovorot\TorgBundle\Entity\SearchSeo;
use Torgovorot\TorgBundle\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

class PublicContentExtension extends \Twig_Extension
{
    
    private $em;
    
    private $rend;
    
    private $router;
    
    public function __construct(EntityManager $em, Router $router)
    {
        $this->em = $em;
        $this->router = $router;
        //$this->rend = $render;
    }

    
    public function initRuntime(\Twig_Environment $environment)
    {
        
        $this->rend = $environment;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            
            'getbanner' => new \Twig_Function_Method($this, 'getBanners'),
            'getRecommended' => new \Twig_Function_Method($this, 'getRecommended'),
            'getlast' => new \Twig_Function_Method($this, 'getLastAdded'),
            'profmenu' => new \Twig_Function_Method($this, 'getProfMenu'),
            'cats' => new \Twig_Function_Method($this, 'getCategoriesForJobs'),
            'getAddr' => new \Twig_Function_Method($this, 'getAddressByAddrId'),
            'getCat' => new \Twig_Function_Method($this, 'getCatNameById'),
            'getPhoto' => new \Twig_Function_Method($this, 'getImageById'),
            'getUserPhoto' => new \Twig_Function_Method($this, 'getImageByOwnerId'),
            'getRightBanners' => new \Twig_Function_Method($this, 'getBannerForRight'),
            'getMapCoords' => new \Twig_Function_Method($this, 'getMapCoords'),
            'getAllMapCoords' => new \Twig_Function_Method($this, 'getAllMapCoords'),
            'getUserInfoById' => new \Twig_Function_Method($this, 'getUserInfoById'),
            'getPCount' => new \Twig_Function_Method($this, 'getPhotoCount'),
            'generateBottomMenu' => new \Twig_Function_Method($this, 'generateBottomMenu'),
            'generateTopMenu' => new \Twig_Function_Method($this, 'generateTopMenu'),
            'getAdsByCatId'=> new \Twig_Function_Method($this, 'getAdsByCatId'),
            'price_temp' => new \Twig_Function_Method($this, 'price_temp'),
            'randomUrl' => new \Twig_Function_Method($this, 'randomUrl'),
            'getCatalogCats' => new \Twig_Function_Method($this, 'getCatalogCats'),
            //'test' => new \Twig_Function_Method($this, 'getCategoriesAll'),
            'currentCat' => new \Twig_Function_Method($this, 'getCurrentCat')
        );
    }
   
    /**
     * Converts a string to time
     *
     * @param int $id
     * 
     */
    
    public function getLastAdded()
    {
        return "last";
    }
    
    private function getRecommendList($params = array())
    {
        $array = array();
        
        foreach($params as $pName => $parameters)  $$pName = $parameters;
        
        $em = $this->em;
        
        $q_add = false;
        
        $ads = "AdsRealty";
        
        if($type == 1)
        {
            $ads = "AdsRealty";
        }
        elseif($type == 2)
        {
            $ads = "AdsVacance";
        }
        elseif($type == 3)
        {
            $ads = "AdsResume";
        }
        elseif($type == 4)
        {
            $ads = "AdsGoods";
        }
        elseif($type == 5)
        {
            $ads = "AdsEvents";
        }
        elseif($type == 6)
        {
            $ads = "AdsDiscounts";
        }
        elseif($type == 8)
        {
            $ads = "AdsCars";
        }
        if(isset($reccomend))
        {
            if($reccomend == 1)
            {
                $q_add .= "p.recommend = 1 ";
            }
            else
            {
                $q_add .= "";
            }
        }
        if(isset($spec))
        {
            
            if($spec == 1)
            {
                if($q_add != "")
                {
                    $q_add = $q_add."AND ";
                }
                $q_add .= "p.special = 1 ";
            }
            else
            {
                $q_add .= "";
            }
        }
        if($q_add != "")
        {
            $q_add = "WHERE ".$q_add." and p.adsState = 1";
        }
        else
        {
            $q_add = "WHERE p.adsState = 1";
        }
        //form echo
            if($param == "simple")
            {
                $query = $this->em->createQuery(
                    "SELECT p
                    FROM TorgovorotTorgBundle:$ads p
                    $q_add
                    "
                );
                
                $array = $query->getResult();               
            }
            elseif($param == "new")
            {
                $query = $this->em->createQuery(
                    "SELECT p
                    FROM TorgovorotTorgBundle:$ads p
                    $q_add
                    ORDER BY p.time DESC"
                );
                
                $array = $query->getResult();
            }
            elseif($param == "random")
            {
                $query = $this->em->createQuery(
                    "SELECT p
                    FROM TorgovorotTorgBundle:$ads p
                    $q_add
                    "
                );
                
                //echo $q_add;
                
                $array = $query->getResult();
                
                shuffle($array);
                
            }
            
        
        $limit = $count; //current count on page
        
        $array = $this->getList(1, $array, $limit);   
        
        return $array;
    }
    /**
     * Converts a string to time
     *
     * 
     * 
     */
    public function getRecommended($type = 0, $param, $count = 3, $html = "li", $vip = 0, $emergency = 0, $info = 0, $recommend = 0, $spec = 0)
    {
        
        $params = array();
        
        $params['type'] = $type;
        
        $params['param'] = $param;
        
        $params['count'] = $count;
        
        $params['reccomend'] = $recommend;
        
        $params['spec'] = $spec;
        
        $objects = $this->getRecommendList($params);
        
            return $this->rend->render('TorgovorotTorgBundle:public:items.html.twig', array(
                'items' => $objects ,
                'type' => $type,
                'tag' => $html,
                'vip' => $vip,
                'emergency' => $emergency,
                'info' => $info
            ));
        
        
    }
    
    //test function
    public function getCategoriesForJobs($type = 2, $count = 7, $param = "")
    {
        $em = $this->em;
        
        $cats = array();
        
        $class = "AdsVacance";
        
        switch ($type)
        {
            case 2:
                $class = "AdsVacance";
                break;
            case 3:
                $class = "AdsResume";
                break;
        }
        
        $objects = $em->getRepository("TorgovorotTorgBundle:$class")->findAll();
        
        /*
        //echo "<pre>";
        foreach($objects as $object)
        {
            //$vac = new AdsVacance();
            //$vac = $vc;
            
            $selected_cats = $type == 2 ? $object->getVacancyIds() : $object->getJobsIds();
            
            $selected_arr = explode(";", $selected_cats);
            
            foreach($selected_arr as $one_arr)
            {
                $cats[$one_arr][] = $object->getId();
            }
        }
        
        uasort($cats, function ($a, $b) {
            
            if ($a == $b) 
            {
                return 0;
            }
            return ($a > $b) ? -1 : 1;
            
        });
        //print_r($cats);
        $cats_new = array();
        
        foreach($cats as $key=>$new_cat)
        {
            $cat_one_new = $em->getRepository("TorgovorotTorgBundle:Cats")->find($key);
            
            if(!in_array($cat_one_new, $cats_new) and count($cats_new) <= $count)
            {
                $cat_one_new->setCount(count($cats[$key]));
                $cats_new[] = $cat_one_new;
            }
        }
        
        
        //print_r($cats_new);
        //echo "</pre>";
        return $this->rend->render('TorgovorotTorgBundle:public:items_cats.html.twig', array(
            'items' => $cats_new ,
            'type' => $type
        ));*/
        
        $cat_new = array();
        
        $count_cats = array();
        
        $cats = $em->createQuery("select c from TorgovorotTorgBundle:Cats as c where c.type != 3 ORDER BY c.topPos DESC")
                 ->getResult();
        
        foreach($objects as $entity)
        {
            
            $selected_cats = $type == 2 ? $entity->getVacancyIds() : $entity->getJobsIds();
            
            $selected_arr = explode(";", $selected_cats);
            
            foreach($cats as $key=>$cat)
            {
                if(in_array($cat->getId(), $selected_arr))
                {
                    /*$ncat = new Cats();
                    $ncat = $cat;
                    $ncat->setCount($ncat->getCount()+1);
                    echo $entity->getId()."<br>";
                    break;*/
                    $count_cats[$cat->getId()][] = $entity;
                }
                elseif(!in_array($cat->getId(), $selected_arr) and count($count_cats) < $count)
                {
                    $count_cats[$cat->getId()] = array();
                }
                /*else
                {
                    if($cat->getCount() == "")
                    {
                        unset($cats[$key]);
                    }
                    
                }*/
            }
        }
        //echo "<pre>";
        //print_r($count_cats);
        //echo "</pre>";
        foreach($count_cats as $key=>$value)
        {
            $ccat = $em->getRepository("TorgovorotTorgBundle:Cats")->find($key);
            if(!empty($value))
                    $ccat->setCount(count($value));
            else
                $ccat->setCount(0);
            
            
            $cat_new[] = $ccat;
        }
        
        uasort($cat_new, function ($f1, $f2) {
            
            if($f1->getTopPos() > $f2->getTopPos()) return -1;
            elseif($f1->getTopPos() < $f2->getTopPos()) return 1;
            else return 0;
            
        });
        
        $cat_new = array_slice($cat_new, 0, $count);
        
        return $this->rend->render('TorgovorotTorgBundle:public:items_cats.html.twig', array(
            'items' => $cat_new ,
            'type' => $type
        ));
    }
    
    public function getCategoriesForJobs_deprecated($type = 1, $count = 7, $param = "")
    {
        $cat = array();
        
        $em = $this->em;
        
        
        
        if($type == 1)
        {
            
        }
        elseif($type == 2)
        {
            $temp_arr = array(); //temporary array for preventing cat's repeats 
            
            $binds = $em->getRepository("TorgovorotTorgBundle:CatBind")->findBy(array('mainId' => 2));
            
            /**
             * First, add cats with vacancies
             */
            
            foreach($binds as $one_cat)
            {
                $pod_id = $one_cat->getPodId();
                
                $vacs = $em->getRepository('TorgovorotTorgBundle:AdsVacance')->findAll();
                
                foreach ($vacs as $vac)
                {
                    $jobs = $vac->getVacancyIds();
                    if($jobs != "")
                    {
                        $arr_jobs = explode(";", $jobs);
                        if(in_array($pod_id, $arr_jobs) and count($cat) <= $count)
                        {
                            $temp_arr[] = $pod_id;
                            $_cat = $em->getRepository("TorgovorotTorgBundle:Cats")->find($pod_id);
                            if(!in_array($_cat, $cat))
                            {
                                $cat[] = $_cat;
                            }
                            
                            //$cat = array_unique($cat);
                        }
                    }
                }
            }
            //shuffle($cat);
            /**
             * Now, we will to check if our array has enough cats, if no, we will add more
             */
            
            if(count($cat) < 3)
            {
                while(count($cat) < 3)
                {
                    $one_cat = $binds[rand(0, (count($binds)-1))];
                    $catty = $em->getRepository("TorgovorotTorgBundle:Cats")->find($one_cat->getPodId());
                    if(!in_array($catty, $cat))
                    {
                        $cat[] = $catty;
                    }
                }
            }
            if(count($cat) < 3)
            {
                while(count($cat) < 3)
                {
                    $one_cat = $binds[rand(0, (count($binds)-1))];
                    $catty = $em->getRepository("TorgovorotTorgBundle:Cats")->find($one_cat->getPodId());
                    if(!in_array($catty, $cat))
                    {
                        $cat[] = $catty;
                    }
                }
            }
            /*echo "<pre>";
            print_r($cat);
            echo "</pre>";*/
            
            
        }
        elseif($type == 3)
        {
            $temp_arr = array(); //temporary array for preventing cat's repeats 
            
            $binds = $em->getRepository("TorgovorotTorgBundle:CatBind")->findBy(array('mainId' => 3));
            
            /**
             * First, add cats with vacancies
             */
            
            foreach($binds as $one_cat)
            {
                $pod_id = $one_cat->getPodId();
                
                $vacs = $em->getRepository('TorgovorotTorgBundle:AdsResume')->findAll();
                
                foreach ($vacs as $vac)
                {
                    $jobs = $vac->getJobsIds();
                    if($jobs != "")
                    {
                        $arr_jobs = explode(";", $jobs);
                        if(in_array($pod_id, $arr_jobs) and count($cat) <= $count)
                        {
                            $temp_arr[] = $pod_id;
                            $_cat = $em->getRepository("TorgovorotTorgBundle:Cats")->find($pod_id);
                            if(!in_array($_cat, $cat))
                            {
                                $cat[] = $_cat;
                            }
                        }
                    }
                }
            }
            //shuffle($cat);
            /**
             * Now, we will to check if our array has enough cats, if no, we will add more
             */
            
            if(count($cat) < 3)
            {
                while(count($cat) < 3)
                {
                    $one_cat = $binds[rand(0, (count($binds)-1))];
                    $catty = $em->getRepository("TorgovorotTorgBundle:Cats")->find($one_cat->getPodId());
                    if(!in_array($catty, $cat))
                    {
                        $cat[] = $catty;
                    }
                }
            }
        }
        
        return $this->rend->render('TorgovorotTorgBundle:public:items_cats.html.twig', array(
            'items' => $cat ,
            'type' => $type
        ));
    }
    
    public function getAdsByCatId($cat_id = 0, $params = array())
    {
        if($cat_id != 0)
        {
            $array = array();
        
            foreach($params as $pName => $parameters)  $$pName = $parameters;
        
            $em = $this->em;
        
            $ads = "AdsRealty";
            
            switch ($cat_id) 
            {
                case 1:
                    $ads = "AdsRealty";
                    break;
                case 2:
                    $ads = "AdsVacance";
                    break;
                case 3:
                    $ads = "AdsResume";
                    break;
                case 4:
                    $ads = "AdsGoods";
                    break;
                case 5:
                    $ads = "AdsEvents";
                    break;
                /*case 6:
                    $ads = "Users";
                    break;*/
                case 8:
                    $ads = "AdsCars";
                    break;
            }
            $object = $em->getRepository("TorgovorotTorgBundle:$ads")->findBy(array('catId'=>$cat_id));
            
            return $object;
        }
    }
    
    //Banner system
    
    private function getBannersFunc($count, $param, $id = 0, $pos = 0)
    {
        $em = $this->em;
        
        $array = array();
        
        if($id == 0)
        {
            if($pos == 0)
            {
                if($param == "simple")
                {
                    $array = $em->getRepository('TorgovorotTorgBundle:Banners')->findAll();
                }
                elseif($param == "new")
                {
                    $query = $this->em->createQuery(
                            "SELECT p
                            FROM TorgovorotTorgBundle:Banners p
                            ORDER BY p.time DESC"
                    );
                
                    $array = $query->getResult();
                }
                elseif($param == "random")
                {
                    $query = $this->em->createQuery(
                            "SELECT p
                            FROM TorgovorotTorgBundle:Banners p"
                    );
                
                    $array = $query->getResult();
            
                    shuffle($array);
                }
            }
            elseif($pos != 0)
            {
                if($param == "simple")
                {
                    $array = $em->getRepository('TorgovorotTorgBundle:Banners')->findBy(array("position" => $pos));
                }
                elseif($param == "new")
                {
                    $query = $this->em->createQuery(
                            "SELECT p
                            FROM TorgovorotTorgBundle:Banners p
                            WHERE p.position = $pos
                            ORDER BY p.time DESC"
                    );
                
                    $array = $query->getResult();
                }
                elseif($param == "random")
                {
                    $array = $em->getRepository('TorgovorotTorgBundle:Banners')->findBy(array("position" => $pos));
            
                    shuffle($array);
                }
            }
        }
        elseif($id != 0 && $id > 0)
        {
            $array[] = $em->getRepository('TorgovorotTorgBundle:Banners')->find($id);
        }
            
        $limit = $count; //current count on page
        
        $array = $this->getList(1, $array, $limit);
        
        return $array;
    }
    
    //generate banner
    public function getBanners($count, $param, $id = 0, $width = 0, $height = 0, $class="", $pos = 0)
    {
        $objects = $this->getBannersFunc($count, $param, $id, $pos);
               
        return $this->rend->render('TorgovorotTorgBundle:public:banners.html.twig', array(
            'items' => $objects,
            'type' => "banner",
            'width' => $width,
            'height' => $height,
            'class' => $class
        ));
    }
    
    //Help functions
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
    
    public function getProfMenu()
    {
        
        return $this->rend->render('TorgovorotTorgBundle:Default:profmenu.html.twig');
    }
    
    public function getAddressByAddrId($id, $count = "one")
    {
        $em = $this->em;
        $addr;
        if($count == "one")
        {
            $xplode = explode(";",$id);
            
            $addr = new \Torgovorot\TorgBundle\Entity\Address();
        
            $addr = $em->getRepository('TorgovorotTorgBundle:Address')->find($xplode[0]);
        }
        elseif($count == "all")
        {
            $addr = array();
            
            $xplode = explode(";",$id);
            
            foreach($xplode as $value)
            {
                $object = $em->getRepository('TorgovorotTorgBundle:Address')->find($value);
                if($object != null)
                {
                    $addr[] = $object;
                }
            }
        }
        
        return $addr;
    }
    
    public function getCatNameById($id)
    {
        $em = $this->em;
        
        $cat = new Cats();
        
        $cat = $em->getRepository("TorgovorotTorgBundle:Cats")->find($id);
        
        return $cat;
    }
    
    public function getImageById($id, $count = 0, $extension = "none")
    {
        $em = $this->em;
        
        $photo = "none";
        
        $split = explode(";", $id);
        
        if($id == 0 or $id == "")
        {
            return $photo;
        }
        
        if(count($split) == 1 and $count == 0)
        {
            $photo = $em->getRepository("TorgovorotTorgBundle:AdvImages")->findOneBy(array('id' => $id));
            if($photo != null)
            {
                $photo = $photo->getIName();
            
                if($extension != "none")
                {
                    //print_r($photo);
                    $photo1 = explode(".", $photo);
                    $photo = $photo1[0].".".$extension;
                }
            }
            
        }
        elseif(count($split) > 1 and $count == 0)
        {
            foreach($split as $key => $value)
            {
                $photo = $em->getRepository("TorgovorotTorgBundle:AdvImages")->findOneBy(array('id' => $value));
                
                $photo = $photo->getIName();
                //print_r($photo);
                if($extension != "none")
                {
                    $photo1 = explode(".", $photo);
                    
                    $photo = $photo1[0].".".$extension;
                }
                
                break;
            }
        }
        elseif(count($split) > 0 and $count > 0)
        {
            $photo = array();
            $ii = 0;
            foreach($split as $key => $value)
            {
                $object = $em->getRepository("TorgovorotTorgBundle:AdvImages")->findOneBy(array('id' => $value));
                if($object != null)
                {
                    //print_r($photo);
                    if($extension == "none")
                    {
                        $photo[] = $object->getIName();
                    }
                    else
                    {
                        $p = explode(".", $object->getIName());
                        $photo[] = $p[0].".$extension";
                    }
                    $ii++;
                    if($ii == $count)
                    {
                        break;
                    }
                }
            }
        }
        
        return $photo;
    }
    
    public function getImageByOwnerId($id, $extension = "none")
    {
        $em = $this->em;
        
        $photo = "none";
        if($id > 0)
        {
            $user = $em->getRepository("TorgovorotTorgBundle:Users")->find($id);
        
            $photo = $em->getRepository("TorgovorotTorgBundle:AdvImages")->findOneBy(array('id' => $user->getPhoto()));
            if($photo != null)
            {
                $photo = $photo->getIName();
            }
            
            
            if($extension != "none")
            {
                $photo1 = explode(".", $photo);
                if($photo1[0] != "")
                {
                    $photo = $photo1[0].".$extension";
                }
                else
                {
                    $photo = "none";
                }
            }
        }
        
        return $photo;
    }
    
    public function getPhotoCount($data)
    {
        $count = explode(";", $data);
        
        return count($count);
    }
    
    public function getBannerForRight($type = 0, $param, $count = 3, $html = "div", $vip = 0, $emergency = 0, $info = 0)
    {
        $params = array();
        
        $params['type'] = $type;
        
        $params['param'] = $param;
        
        $params['count'] = $count;
        
        $params['$reccomend'] = 1;
        
        $objects = $this->getRecommendList($params);
        
            return $this->rend->render('TorgovorotTorgBundle:public:items.html.twig', array(
                'items' => $objects ,
                'type' => $type,
                'tag' => $html,
                'vip' => $vip,
                'emergency' => $emergency,
                'info' => $info
            ));
    }
    
    public function getMapCoords($address, $type = 0)
    {
        $params = array(
            'geocode' => "$address", // адрес
            'format'  => 'json',                          // формат ответа
            'results' => 1,                               // количество выводимых результатов
            'key'     => 'AJnOcFMBAAAA26hXKwMAcTZewTyJgv7I-bVTeTD_Hp6stBEAAAAAAAAAAADbfYC-lpUO_hEmZEJx49kH8O5x4g==',                           // ваш api key
        );
        if($type == 1)
        {
            $xml = simplexml_load_file('http://geocode-maps.yandex.ru/1.x/?'. http_build_query($params, '', '&'));
            
            print_r($xml);
        }
        else if($type == 0)
        {
            //echo $response->response->GeoObjectCollection->metaDataProperty->GeocoderResponseMetaData->found;
            $response = json_decode(file_get_contents('http://geocode-maps.yandex.ru/1.x/?' . http_build_query($params, '', '&')));
            //print_r($response);
            if ($response->response->GeoObjectCollection->metaDataProperty->GeocoderResponseMetaData->found > 0)
            {
                return $response->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
            }
            else
            {
                return "37.619899 55.753676";
            }
        }
    }
    
    public function getAllMapCoords()
    {
        
        $addrs_ids = array();
        $addrs = array();
        
        $realties = new AdsRealty();
        $vacancies = new AdsVacance();
        $resumes = new AdsResume();
        $products = new AdsGoods();
        $events = new AdsEvents();
        $cars = new AdsCars();
        
        //All realties
        $realties = $this->em->getRepository("TorgovorotTorgBundle:AdsRealty")->findAll();
        
        //All vacancies
        //$vacancies = $this->em->getRepository("TorgovorotTorgBundle:AdsVacance")->findAll();
        
        //All resumes
        //$resumes = $this->em->getRepository("TorgovorotTorgBundle:AdsResume")->findAll();
        
        //All products
        //$products = $this->em->getRepository("TorgovorotTorgBundle:AdsGoods")->findAll();
        
        //All events
        //$events = $this->em->getRepository("TorgovorotTorgBundle:AdsEvents")->findAll();
        
        //All Autos
        //$cars = $this->em->getRepository("TorgovorotTorgBundle:AdsCars")->findAll();
        
        //add all addresses from realties
        if($realties)
        {
            foreach($realties as $realty)
            {
                if($realty != null)
                {
                    $addrs_ids_temp = explode(";",$realty->getAddrId());
                    
                    foreach($addrs_ids_temp as $addr_id_temp)
                    {
                        if($addr_id_temp != "")
                        {
                            $arr_realty_temp = array(
                                'addr_id' => $addr_id_temp,
                                'title' => $realty->getTitle(),
                                'descr' => $realty->getDescription(),
                                'id' => $realty->getId()
                            );
                            
                            $addrs_ids[] = $arr_realty_temp;
                        }
                    }
                }
            }
        }
        
        //add all addresses from vacancies
        /*if($vacancies)
        {
            foreach($vacancies as $vacancy)
            {
                if($vacancy != null)
                {
                    $addrs_ids_temp = explode(";",$vacancy->getAddrId());
                    
                    foreach($addrs_ids_temp as $addr_id_temp)
                    {
                        if($addr_id_temp != "")
                        {
                            $addrs_ids[] = $addr_id_temp;
                        }
                    }
                }
            }
        }
        
        //add all addresses from resumes
        if($resumes)
        {
            foreach($resumes as $resume)
            {
                if($resume != null)
                {
                    $addrs_ids_temp = explode(";",$resume->getAddrId());
                    
                    foreach($addrs_ids_temp as $addr_id_temp)
                    {
                        if($addr_id_temp != "")
                        {
                            $addrs_ids[] = $addr_id_temp;
                        }
                    }
                }
            }
        }
        
        //add all addresses from products
        if($products)
        {
            foreach($products as $product)
            {
                if($product != null)
                {
                    $addrs_ids_temp = explode(";",$product->getAddrId());
                    
                    foreach($addrs_ids_temp as $addr_id_temp)
                    {
                        if($addr_id_temp != "")
                        {
                            $addrs_ids[] = $addr_id_temp;
                        }
                    }
                }
            }
        }
        
        //add all addresses from events
        if($events)
        {
            foreach($events as $event)
            {
                if($event != null)
                {
                    $addrs_ids_temp = explode(";",$event->getAddrId());
                    
                    foreach($addrs_ids_temp as $addr_id_temp)
                    {
                        if($addr_id_temp != "")
                        {
                            $addrs_ids[] = $addr_id_temp;
                        }
                    }
                }
            }
        }
        
        //add all addresses from cars
        if($cars)
        {
            foreach($cars as $car)
            {
                if($car != null)
                {
                    $addrs_ids_temp = explode(";",$car->getAddrId());
                    
                    foreach($addrs_ids_temp as $addr_id_temp)
                    {
                        if($addr_id_temp != "")
                        {
                            $addrs_ids[] = $addr_id_temp;
                        }
                    }
                }
            }
        }*/
        
        $response_html = "";
        
        foreach($addrs_ids as $addrs_entity)
        {
            
            $addr = new Address();
            
            $addr = $this->em->getRepository("TorgovorotTorgBundle:Address")->find($addrs_entity['addr_id']);
            
            $addr_str = $addr->getCity().', ул. '.$addr->getStreet().', '.$addr->getHouse();
            
            //print_r($coords);

            $params = array(
                'geocode' => $addr_str, // адрес
                'format'  => 'json',                          // формат ответа
                'results' => 1,                               // количество выводимых результатов
                'key'     => 'ACfnyFMBAAAAIzmPTwIAsnteKY9dXTzxIJi6pFyt2sDM0boAAAAAAAAAAAA5ntYBOBw_Fw_2FNwP7H4kAZLkiA==',                           // ваш api key
            );

            $response = json_decode(file_get_contents('http://geocode-maps.yandex.ru/1.x/?' . http_build_query($params, '', '&')));
            
            $coords = "";
            
            if ($response->response->GeoObjectCollection->metaDataProperty->GeocoderResponseMetaData->found > 0)
            {
                $coords = $response->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;
            }
            
            $coords_arr = explode(" ", $coords);
            
            /*echo "<pre>";
            print_r($coords_arr);
            echo "</pre>";*/
            
            $temp_arr = array(
                'url' => $this->router->generate("object", array("id" => $addrs_entity['id'])),
                'title' => $addrs_entity['descr'],
                'city' => $addr->getCity(),
                'street' => $addr->getStreet(),
                'house' => $addr->getHouse(),
                'office' => $addr->getOffice(),
                'x' => $coords_arr[0],
                'y' => $coords_arr[1]
            );
            
            $addrs[] = $temp_arr;
        }
        
        foreach($addrs as $addr)
        {
            $tmp_string = "[ '".$addr['url']."', '".$addr['title']."', '".$addr['city']."', '".$addr['street']."', '".$addr['house']."', '".$addr['office']."', '".$addr['x']."', '".$addr['y']."' ]";
            
            $response_html .= $tmp_string.",";
        }
        
        //echo "<pre>";
        //print_r($addrs);
        //echo "</pre>";
        //return $addrs;
        return $response_html;
    }
    
    public function getSpecOffers($type, $count, $params = array())
    {
        
    }
    
    public function price_temp($price)
    {
        //$split = explode("", $price);
        
        $price = $price."";
        
        $new_price = "";
        $ii = 1;
        
        for($i = 0; $i > -strlen($price); $i--)
        {
            if($ii == 4)
            {
                $new_price = substr($price,$i-1,1)." ".$new_price;
                $ii = 1;
            }
            else
            {
                $new_price = substr($price,$i-1,1).$new_price;
            }
            $ii++;
        }
        return $new_price;
    }
    
    public function getUserInfoById($id)
    {
        $user = NULL;
        
        $em = $this->em;
        
        $user = $em->getRepository("TorgovorotTorgBundle:Users")->find($id);
        
        return $user;
    }
    
    public function generateBottomMenu()
    {
        $menu = array();
        
        $menu = $this->em->getRepository("TorgovorotTorgBundle:Pages")->findBy(array("bottomMenu" => 1));
        
        return $menu;
    }
    
    public function generateTopMenu()
    {
        $menu = array();
        
        $menu = $this->em->getRepository("TorgovorotTorgBundle:Pages")->findBy(array("topMenu" => 1));
        
        return $menu;
    }
    
    public function randomUrl()
    {
        $seo = new SearchSeo();
        
        $objects = $this->em->getRepository("TorgovorotTorgBundle:SearchSeo")->findAll();
        
        $max = count($objects)-1;
        
        $seo = $objects[rand(0,$max)];
        
        return $seo->getSearchStr();
    }
    
    public function getCatalogCats($catalog_id)
    {
        $cats = $this->em->getRepository("TorgovorotTorgBundle:Cats")->findBy(array('type' => $catalog_id));
        
        $links = "";
        
        shuffle($cats);
        
        $ii = 0;
        
        foreach($cats as $key=>$cat)
        {
            $ii++;
            if($cat != null and $ii <6)
            {
                $link = $this->router->generate("goods_cats", array("cat_id" => $cat->getId()));
                $links .= "<a href=\"$link\">".$cat->getName()."</a> ";
            }
        }
        
        return $links;
    }
    
    public function getCurrentCat($id)
    {
        $cat = new Cats();
        
        $entity = $this->em->getRepository("TorgovorotTorgBundle:Cats")->find($id);
        
        if($entity != null)
        {
            $cat = $entity;
        }
        
        return $cat;
    }
    
    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'Recommends';
    }
    
}

class AllProdEntity
{
    
    private $title;
    
    private $price;
    
    private $descr;
    
    private $emergency;
    
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }
    
    
    public function getPrice()
    {
        return $this->price;
    }
    
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }
    
    
    public function getDescr()
    {
        return $this->descr;
    }
    
    public function setDescr($descr)
    {
        $this->descr = $descr;
        return $this;
    }
    
    
    public function getEmergency()
    {
        return $this->emergency;
    }
    
    public function setEmergency($emerg)
    {
        $this->emergency = $emerg;
        return $this;
    }
}
