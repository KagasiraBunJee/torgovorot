<?php

namespace Torgovorot\TorgBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;

use Torgovorot\TorgBundle\Form\Type\RegistrationType;
use Torgovorot\TorgBundle\Form\Model\Registration;
//forms
use Torgovorot\TorgBundle\Form\UsersType;
use Torgovorot\TorgBundle\Form\AddressType;
use Torgovorot\TorgBundle\Form\CatsType;
use Torgovorot\TorgBundle\Form\AdsGoodsType;
use Torgovorot\TorgBundle\Form\AdsVacanceType;
use Torgovorot\TorgBundle\Form\AdsResumeType;
use Torgovorot\TorgBundle\Form\AdsCarsType;
use Torgovorot\TorgBundle\Form\BannersType;
use Torgovorot\TorgBundle\Form\AdsEventsType;
use Torgovorot\TorgBundle\Form\AdsDiscountsType;
use Torgovorot\TorgBundle\Form\LabelsType;
use Torgovorot\TorgBundle\Form\CharacteristicsType;
use Torgovorot\TorgBundle\Form\CarsType;
use Torgovorot\TorgBundle\Form\CarsMarkType;
//endforms
use Torgovorot\TorgBundle\Entity\Users;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Torgovorot\TorgBundle\Entity\Paginator;
use Torgovorot\TorgBundle\Helper\Uploader\Document;
use Torgovorot\TorgBundle\Helper\Processing\ProcessItem;
use Torgovorot\TorgBundle\Helper\InfoDataBase;
use Torgovorot\TorgBundle\Entity\AdsRealty;
//realty databases
use Torgovorot\TorgBundle\Entity\ChApartment;
use Torgovorot\TorgBundle\Entity\ChComm;
use Torgovorot\TorgBundle\Entity\ChGarage;
use Torgovorot\TorgBundle\Entity\ChHouse;

use Torgovorot\TorgBundle\Entity\GarageType;
use Torgovorot\TorgBundle\Entity\RealtyType;
use Torgovorot\TorgBundle\Entity\Timetable;
use Torgovorot\TorgBundle\Entity\Convinience;
use Torgovorot\TorgBundle\Entity\CommType;
use Torgovorot\TorgBundle\Entity\GoodsSellTypes;
use Torgovorot\TorgBundle\Entity\HouseMaterial;
use Torgovorot\TorgBundle\Entity\HousePlaning;
use Torgovorot\TorgBundle\Entity\HouseType;
use Torgovorot\TorgBundle\Entity\Videos;
use Torgovorot\TorgBundle\Entity\Address;
//end realty databases
use Torgovorot\TorgBundle\Entity\AdsResume;
use Torgovorot\TorgBundle\Entity\AdsGoods;
use Torgovorot\TorgBundle\Entity\AdsVacance;
use Torgovorot\TorgBundle\Entity\AdsEvents;
use Torgovorot\TorgBundle\Entity\AdsVitrina;
use Torgovorot\TorgBundle\Entity\AdsDiscounts;
use Torgovorot\TorgBundle\Entity\AdsCars;

use Torgovorot\TorgBundle\Entity\Complaints;

use Torgovorot\TorgBundle\Entity\Banners;
use Torgovorot\TorgBundle\Entity\Pages;
use Torgovorot\TorgBundle\Entity\AdsList;
use Torgovorot\TorgBundle\Entity\Cats;
use Torgovorot\TorgBundle\Entity\CatBind;
use Torgovorot\TorgBundle\Entity\Labels;
use Torgovorot\TorgBundle\Entity\Characteristics;
use Torgovorot\TorgBundle\Entity\CharacterBind;
use Torgovorot\TorgBundle\Entity\Cars;
use Torgovorot\TorgBundle\Entity\CarsMark;

use Symfony\Component\HttpFoundation\File\File;
use Torgovorot\TorgBundle\Entity\AdminConfig;

class AdminController extends Controller 
{
    
    private $proc;
    
    public function __construct()
    {
        //$this->proc = new ProcessItem($this->getDoctrine()->getEntityManager());
        //$this->info = new InfoDataBase($this->getDoctrine()->getEntityManager());
        /*$us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;*/
    }
    
    public function mainAction(Request $request)
    {
        /*$request = $this->getRequest();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }
        
        return $this->render(
            'TorgovorotTorgBundle:Account:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
            )
        );*/
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        $user = $this->get('security.context')->getToken()->getUser();
        
        
        return $this->render('TorgovorotTorgBundle:Admin:admin_main.html.twig');
    }
    
    public function addAction($param = 0, Request $request = null)
    {
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        if($param > 0)
        {
            $form = $this->createFormBuilder()
                     ->setAction($this->generateUrl('admin_panel_add_ads',array('param' => $param)));
            $em = $this->getDoctrine()->getEntityManager();
            $user = $this->get('security.context')->getToken()->getUser();
            $mainId = 0;
            $form->add('title','text');
            //events
            if($param == 1)
            {
                $mainId = 5;
                
                $cat_bind = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:CatBind')->findBy(array('mainId' => $mainId));
        
                $cat_arr = array();
        
                foreach($cat_bind as $cat_b)
                {
                    $cat = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Cats')->findOneBy(array('id' => $cat_b->getPodId()));
            
                    $cat_arr[$cat->getId()] = $cat->getName();
            
                }
                
                $form->add('age', 'text')
                     ->add('else', 'textarea')
                     ->add('length', 'text')
                     ->add('director', 'text')
                     ->add('genre', 'text')   
                     ->add('descr', 'textarea',array(
                         'required' => false
                     ))
                     ->add('cats','choice', array(
                    'choices' => $cat_arr,
                    'expanded' => false,
                    'empty_value' => false,
                    'required'    => false))
                     ->add('time', 'date', array(
                    'data' => new \DateTime()))
                     ->add('photo','file', array(
                         'required'    => false,
                         "attr" => array(
                            "accept" => "image/*",
                            "multiple" => "multiple",
                            )
                     ));
            }
            //discounts
            elseif($param == 2)
            {
                $mainId = 6;
                
                $cat_bind = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:CatBind')->findBy(array('mainId' => $mainId));
        
                $cat_arr = array();
        
                foreach($cat_bind as $cat_b)
                {
                    $cat = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Cats')->findOneBy(array('id' => $cat_b->getPodId()));
            
                    $cat_arr[$cat->getId()] = $cat->getName();
            
                }
                
                $form->add('discount', 'textarea', array(
                    'required' => false
                ))
                     ->add('cats','choice', array(
                    'choices' => $cat_arr,
                    'expanded' => false,
                    'empty_value' => false,
                    'required'    => false));                
            }
            
            
            $form->add('save', 'submit');
            
            $form->getForm();        
        
            $get_form = $form->getForm();
        
            $get_form->handleRequest($request);
            
            
            if($get_form->isValid())
            {
                $data = $get_form->getData();
           
                foreach($data as $key=>$val) $$key = $val;
                
                //events
                if($param == 1)
                {
                    $ads = new AdsEvents();
                    
                    $ads->setAgeRestrict($age);
                    
                    if($cats)
                    {
                        $catids = "";
                        foreach($cats as $value)
                        {
                            $catids .= $value.";";
                        }
                        $ads->setCategoryIds($catids);
                        
                    }
                    
                    $ads->setDescription($descr);
                    $ads->setEventTime($time);
                    $ads->setTime(new \DateTime("now"));
                    $ads->setTitle($title);
                    $ads->setDirector($director);
                    $ads->setLength($length);
                    $ads->setAnything($else);
                    $ads->setGenre($genre);
                    
                    if(!empty($photo))
                    {
                        $file = new Document($photo, 0, $this->getDoctrine()->getEntityManager());
                        $photo_id = $file->upload();
                        
                        $ads->setPhotoIds($photo_id);
                    }
                    
                    $em->persist($ads);
                    $em->flush();
                }
                elseif($param == 2)
                {
                    
                }
            }
            
            return $this->render('TorgovorotTorgBundle:Default:ads_add.html.twig', array(
                'form' => $get_form->createView() , 'form_type' => $mainId, 'ads_type' => 'ready_form'
            ));
        }
    }
    
    public function loginAction()
    {
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        $request = $this->getRequest();
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
            'TorgovorotTorgBundle:Account:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
            )
        );
    }
    
    public function updateProfileAction($id, Request $request)
    {
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        $user = new Users();
        
        $user = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Users")->find($id);
        /*
        $form = $this->createFormBuilder()
                     ->setAction($this->generateUrl('admin_profile_view',array('id' => $id)));
        
            
        $form->add('title','text')
             ->add('', '')*/
        $em = $this->getDoctrine()->getEntityManager();
        $form = $this->createForm(new UsersType(),$user);
        
        $form->add('credits', 'text', array(
            'label' => 'Баланс'
        ));
        
        $form->handleRequest($request);
        
        if($form->isValid())
        {
            $data = $form->getData();
            
            $file = $form->get("photo")->getData();
            
            if(isset($file))
            {
                if($file != null)
                {
                    $pid = $this->savePhoto($file, array("type" => "photo"));
                    if($pid != "")
                    {
                        $data->setPhoto($pid);
                    }
                }
            }
            
            $em->persist($data);
            $em->flush();
        }
        
        $addrs = $this->getAddressesByUserId($user->getAddrId());
        
        $vac = $this->getVacanciesByUserId($user->getId());
        
        $res = $this->getResumesByUserId($user->getId());
        
        $goods = $this->getGoodsByUserId($user->getId());
        
        $realty = $this->getRealtiesByUserId($user->getId());
        
        $realty_type = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:RealtyType')->findAll();
        
        $r_arr = array();
        
        foreach ($realty_type as $r)
        {
            $r_arr[$r->getId()] = $r->getName();
        }
        
        return $this->render('TorgovorotTorgBundle:Admin:item_profile.html.twig', array(
                'form' => $form->createView(),
                'addrs' => $addrs,
                'user' => $user,
                'vac' => $vac,
                'res' => $res,
                'goods' => $goods,
                'realty' => $realty,
                'rtype' => $r_arr
            ));
    }
    
    //realtyupdate
    public function updateRealtyAction($id, $param, $id1, Request $request = null)
    {
                
        $form = $this->createFormBuilder()
                     ->setAction($this->generateUrl('admin_realty_view',array('id'=> $id, 'param' => $param, 'id1' => $id1)));
        $em = $this->getDoctrine()->getEntityManager();
        $user = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Users")->find($id);
        
            //Params
            //apartment realty type
            $rtype = array('0' => 'Продам','1' => 'Куплю','2' => 'Сдам','3' => 'Сниму');
            //rooms count
            $rooms = array('1' => '1','2' => '2','3' => '3','4' => '4','5' => '5','6' => '6','7' => 'больше',);
            //convinience
            $conv = array(
                '0' => 'Наличие балкона',
                '1' => 'Наличие холодильника',
                '2' => 'Наличие телевизора',
                '3' => 'Наличие телефона',
                '4' => 'Наличие кондиционера',
                '5' => 'Наличие посудомоечной машины',
                '6' => 'Наличие стиральной машины',
                '7' => 'Наличие нагревателя воды',
            );
            
            //bath type
            $bath_type = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:BathType')->findAll();
            
            $bath_arr = array();
            
            foreach($bath_type as $bath) $bath_arr[$bath->getId()] = $bath->getName();
            
            //house material
            $house_material = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:HouseMaterial')->findAll();
            
            $mat_arr = array();
            
            foreach($house_material as $bath) $mat_arr[$bath->getId()] = $bath->getName();        
            
            //HouseType
            $house_type = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:HouseType')->findAll();
            
            $htype = array();
            
            foreach($house_type as $htype1) $htype[$htype1->getId()] = $htype1->getName();  
            
            //House Planning
            $house_plan = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:HousePlaning')->findAll();
            
            $ptype = array();
            
            foreach($house_plan as $ptype1) $ptype[$ptype1->getId()] = $ptype1->getName();            
        
            
            //converse in house
            $hconv = array(
                '0' => 'Наличие отопления',
                '1' => 'Наличие канализации',
                '2' => 'Наличии водопровода',
                '3' => 'Наличие электричества',
                '4' => 'Наличие газа'
            );
            
            //Commercial house type
            $commtype = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:CommType')->findAll();
            
            $ctype = array();
            
            foreach($commtype as $ctype1) $ctype[$ctype1->getId()] = $ctype1->getName();              
            
            //Garage type
            $gartype = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:GarageType')->findAll();
            
            $gtype = array();
            
            foreach($gartype as $gtype1) $gtype[$gtype1->getId()] = $gtype1->getName();            
            
            //converse in garage
            $gconv = array(
                '0' => 'Наличие отопления',
                '1' => 'Наличие электричества',
                '2' => 'Наличии водопровода',
                '3' => 'Наличие кессона',
                '4' => 'Наличие смотровой ямы'
            );
        $ads = new AdsRealty();
        
        $ads = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsRealty')->find($id1);
        
        if($param != 0)
        {
            
            
            $form->add('rtype','choice', array(
                    'choices' => $rtype,
                    'expanded' => false,
                    'empty_value' => false,
                    'data' => $ads->getAdsType(),
                    'required'    => false))
                 ->add('title','text',array(
                    'data' => $ads->getTitle()
                 ))
                 ->add('price','text', array(
                     'data' => $ads->getPrice()
                 ))
                 ->add('description','textarea', array(
                     'data' => $ads->getDescription(),
                     'required' => false
                 ))
                 ->add('shortDesc','textarea', array(
                     'data' => $ads->getShortDesc(),
                     'required' => false
                 ));            
        }
            
        if($param == 1 or $param == 2)
        {
            //first line of form
            $ch = new ChApartment();
            
            $ch = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:ChApartment')->find($ads->getChId());
            
            $array_data = array();
            
            if($ch->getBalcony() == 1)
            {
                $array_data['0'] = 0;
            }
            if($ch->getRefregirator() == 1)
            {
                $array_data['1'] = 1;
            }
            if($ch->getTv() == 1)
            {
                $array_data['2'] = 2;
            }
            if($ch->getPhone() == 1)
            {
                $array_data['3'] = 3;
            }
            if($ch->getConditiononeer() == 1)
            {
                $array_data['4'] = 4;
            }
            if($ch->getDishwasher() == 1)
            {
                $array_data['5'] = 5;
            }
            if($ch->getWashingmachine() == 1)
            {
                $array_data['6'] = 6;
            }
            if($ch->getBoiler() == 1)
            {
                $array_data['7'] = 7;
            }
            //Second line of form Table "ch_apartment"
              //rooms
                 $form->add('rooms','choice', array(
                    'choices' => $rooms,
                    'expanded' => true,
                    'empty_value' => false,
                    'data' => $ch->getRooms(),
                    'required'    => false))
              //living square
                 ->add('living_square','text', array(
                     'data' => $ch->getLivingSquare()
                 ))
              //general square
                 ->add('general_square','text', array(
                     'data' => $ch->getGeneralSquare()
                 ))
              //floor
                 ->add('floor','text', array(
                     'data' => $ch->getFloor()
                 ))
              //floor count
                 ->add('floor_count','text', array(
                     'data' => $ch->getFloorCount()
                 ))   
              //convinience
                 ->add('conv', 'choice', array(
                    'choices' => $conv,
                    'multiple' => true,
                    'expanded' => true,
                    'data' => $array_data,
                    'empty_value' => false,
                    'required'    => false))
              //bath type
                 ->add('bath_type','choice', array(
                    'choices' => $bath_arr,
                    'expanded' => false,
                    'empty_value' => false,
                    'data' => $ch->getBathType(),
                    'required'    => false))
              //house material
                 ->add('house_mat','choice', array(
                    'choices' => $mat_arr,
                    'empty_value' => false,
                    'expanded' => false,
                    'data' => $ch->getHouseType(),
                    'required'    => false));
        }
        else if($param == 3)
        {
            
            $ch = new ChHouse();
            
            $ch = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:ChHouse')->find($ads->getChId());
            
            $array_data = array();
                        
            if($ch->getConvinient() == 1)
            {
                $array_data['0'] = 0;
            }
            if($ch->getCanalization() == 1)
            {
                $array_data['1'] = 1;
            }
            if($ch->getWater() == 1)
            {
                $array_data['2'] = 2;
            }
            if($ch->getElectricity() == 1)
            {
                $array_data['3'] = 3;
            }
            if($ch->getGas() == 1)
            {
                $array_data['4'] = 4;
            }
            
            $form->add('ttype','choice', array(
                    'choices' => $htype,
                    'expanded' => false,
                    'empty_value' => false,
                    'data' => $ch->getHouseType(),
                    'required'    => true))
                 ->add('plan','choice', array(
                    'choices' => $ptype,
                    'expanded' => true,
                     'data' => $ch->getPlaning(),
                    'empty_value' => false,
                    'required'    => true))
                 ->add('hsquare','text', array(
                     'data' => $ch->getSquareHouse()
                 ))
                 ->add('esquare','text', array(
                     'data' => $ch->getSquareEarth()
                 ))
                 ->add('distance','text', array(
                     'data' => $ch->getDistanceToCity()
                 ))
                 ->add('conv', 'choice', array(
                    'choices' => $hconv,
                    'multiple' => true,
                    'expanded' => true,
                    'empty_value' => false,
                    'data' => $array_data,
                    'required'    => false))
                 ->add('house_mat','choice', array(
                    'choices' => $mat_arr,
                    'empty_value' => false,
                    'expanded' => false,
                    'data' => $ch->getHouseMaterial(),
                    'required'    => false));
        }
        else if($param == 4)
        {
            $ch = new ChComm();
            
            $ch = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:ChComm')->find($ads->getChId());
                        
            $form->add('type','choice', array(
                    'choices' => $ctype,
                    'expanded' => true,
                    'empty_value' => false,
                    'required'    => true))
                 ->add('hsquare','text')
                 ->add('esquare','text');
        }
        else if($param == 5)
        {
            
            $ch = new ChGarage();
            
            $ch = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:ChGarage')->find($ads->getChId());
            
            $array_data = array();
            
            if($ch->getConvinient() == 1)
            {
                $array_data['0'] = 0;
            }
            if($ch->getElectricity() == 1)
            {
                $array_data['1'] = 1;
            }
            if($ch->getWater() == 1)
            {
                $array_data['2'] = 2;
            }
            if($ch->getKesson() == 1)
            {
                $array_data['3'] = 3;
            }
            if($ch->getObservationPit() == 1)
            {
                $array_data['4'] = 4;
            }
            
            $form->add('type','choice', array(
                    'choices' => $gtype,
                    'expanded' => true,
                    'empty_value' => false,
                    'data' => $ch->getGarageType(),
                    'required'    => true))
                 ->add('conv', 'choice', array(
                    'choices' => $gconv,
                    'multiple' => true,
                    'expanded' => true,
                    'data' => $array_data,
                    'empty_value' => false,
                    'required'    => false));
        }
        
        if($param != 0)
        {
            
            $addr1 = new Address();
            
            $addr1 = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Address')->find($ads->getAddrId());
            
            $form->add('photo','file', array(
                     'required'    => false,
                     "attr" => array(
                        "accept" => "image/*",
                        "multiple" => "multiple"
                    )
                 ))
                ->add('video','text', array(
                    'data' => $ch->getVideoId()
                ))
                ->add('city','text', array(
                    'data' => $addr1->getCity()
                ))
                ->add('index','text', array(
                    'data' => $addr1->getIndexN()
                ))
                ->add('street','text', array(
                    'data' => $addr1->getStreet()
                ))
                ->add('house','text', array(
                    'data' => $addr1->getHouse()
                ))
                ->add('flat','text', array(
                    'data' => $addr1->getOffice()
                ))
                ->add('name','text', array(
                    'data' => $addr1->getName()
                ))
                ->add('hints','textarea', array(
                    'data' => $addr1->getHints(),
                    'required' => false
                ))
                
                ->add('save','submit');
        }
        
        $form->getForm();        
        
        $get_form = $form->getForm();
        
        $formView = $get_form->createView();
        
        
        
        $get_form->handleRequest($request);
        
        
        
        if($get_form->isValid())
        {
           $data = $get_form->getData();
           
           $data['ads_id'] = $ads->getId();
           $data['ch_id'] = $ads->getChId();
           $data['addr_id'] = $ads->getAddrId();
           $data['video_id'] = $ch->getVideoId();
           
           if($param == 1)
           {
               
               $this->saveNewFlat($data,$param);
               
               
           }
           else if($param == 2)
           {
               $this->saveNewFlat($data,$param);
           }
           else if($param == 3)
           {
               $this->saveNewHouse($data,$param);
           }
           else if($param == 4)
           {
               $this->saveNewRealty($data,$param);
           }
           else if($param == 5)
           {
               
               $this->saveNewGarage($data,$param);
           }
           else
           {
               return $this->redirect($this->generateUrl('addRealty', array('param'=> $data['rtype'])));
           }
        
           
           
        }
        return $this->render('TorgovorotTorgBundle:Admin:realty_add.html.twig', array(
                'form' => $formView ,
                'form_type' => $param, 
                'ads_type' => 'realty',
                'item' => $ads
           ));
    }
    
    //res update
    public function updateResAction($id,$id1, Request $request)
    {
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        $user = new Users();
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $user = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Users")->find($id);
        
        $object = new AdsResume();
        
        $object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsResume')->find($id1);
        
        $object->setTimetable(explode(";",$object->getTimetable()));
        $object->setJobsIds(explode(";",$object->getJobsIds()));
        //print_r($object);
        
        $form = $this->createForm(new AdsResumeType($this->getDoctrine()->getEntityManager()),$object);
        
        //set params
        
            $cat_arr = array();
            
            $cat_bind = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:CatBind')->findBy(array('mainId' => 3));
            
            foreach($cat_bind as $cat_b)
            {
                $cat = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Cats')->findOneBy(array('id' => $cat_b->getPodId()));
            
                $cat_arr[$cat->getId()] = $cat->getName();
            
            }
            
            $timetable_arr = array();
            
            $all_time = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Timetable')->findAll();
            
            foreach($all_time as $time)
            {
               $timetable_arr[$time->getId()] = $time->getName();  
            }
        
            //$time = explode(";", $object->getTimetable());    
            //$jobs = explode(";", $object->getJobsIds());
        //end set params
        
        
        $form->add('save','submit', array(
            'label' => 'Сохранить'
        ));
        
        
        $form->handleRequest($request);
        
        if($form->isValid())
        {
            $data = $form->getData();
            
            $data->setJobsIds(join(";", $data->getJobsIds()));
            $data->setTimetable(join(";", $data->getTimetable()));
            $em->persist($data);
            $em->flush();
        }
        
        return $this->render('TorgovorotTorgBundle:Admin:item_addr_profile.html.twig', array(
                'form' => $form->createView(),
                'user' => $user,
                'form_type' => 'resume',
                'cat' => '$jobs',
                'time' => '$time'
            ));
    }
    //vac update
    public function updateVacAction($id,$id1, Request $request)
    {
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        $user = new Users();
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $user = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Users")->find($id);
        
        $object = new AdsVacance();
        
        $object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsVacance')->find($id1);
        
        $object->setTimetableIds(explode(";",$object->getTimetableIds()));
        $object->setVacancyIds(explode(";",$object->getVacancyIds()));
        
        $form = $this->createForm(new AdsVacanceType($em),$object);
        
        
        $form->add('save','submit', array(
            'label' => 'Сохранить'
        ));
        
        
        $form->handleRequest($request);
        
        if($form->isValid())
        {
            $data = $form->getData();
            
            $data->setVacancyIds(join(";", $data->getVacancyIds()));
            $data->setTimetableIds(join(";", $data->getTimetableIds()));
            
            $em->persist($data);
            $em->flush();
        }
        
        return $this->render('TorgovorotTorgBundle:Admin:item_addr_profile.html.twig', array(
                'form' => $form->createView(),
                'user' => $user,
                'form_type' => 'resume',
                'cat' => '$jobs',
                'time' => '$time'
            ));
    }
    //good update
    public function updateGoodAction($id,$id1, Request $request)
    {
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        $user = new Users();
        
        $user = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Users")->find($id);
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $object = new AdsGoods();
        
        $object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsGoods')->find($id1);
        
        $form = $this->createForm(new AdsGoodsType($em),$object);
        
        
        $form->add('save','submit', array(
            'label' => 'Сохранить'
        ));
        
        
        $form->handleRequest($request);
        
        if($form->isValid())
        {
            $data = $form->getData();
            
            $file = $form->get("photo")->getData();
            if($file != null)
                $data->setPhotoIds($this->savePhoto($file, array("type"=>"photo")));
            
            $em->persist($data);
            $em->flush();
        }
        
        return $this->render('TorgovorotTorgBundle:Admin:item_good.html.twig', array(
                'form' => $form->createView(),
                'user' => $user,
                'form_type' => 'resume',
                'cat' => '$jobs',
                'time' => '$time',
                'item' => $object
            ));
    }
    
    public function profileAddrAction($id,$id1, Request $request)
    {
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        $user = new Users();
        
        $user = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Users")->find($id);
        
        $addr = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Address")->find($id1);
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $form = $this->createForm(new AddressType(),$addr);
        
        $form->add('save','submit', array(
            'label' => 'Сохранить'
        ));
        
        $form->handleRequest($request);
        
        if($form->isValid())
        {
            $data = $form->getData();
            
            $em->persist($data);
            $em->flush();
        }
        
        return $this->render('TorgovorotTorgBundle:Admin:item_addr_profile.html.twig', array(
                'form' => $form->createView(),
                'user' => $user
            ));
    }
    
        
    public function bannersAction(Request $request = null)
    {
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        
        $objects = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Banners')->findAll();
        
        $array = $objects;
        
        $offset = $request->get('page') ? $request->get('page') : 1; //current page
        $limit = $request->get('onpage') ? $request->get('onpage') : 10; //current count on page
        $midrange = 7;
        $itemsCount = count($array); // item count
        
        $pagination = new Paginator($itemsCount, $offset , $limit, $midrange);
        
        $array = $this->getList($offset, $array, $limit); 
        
        return $this->render(
            'TorgovorotTorgBundle:Admin:items_banners.html.twig',
            array(
                // last username entered by the user
                'items' => $array,
                'paginator' => $pagination
                
            )
        );
    }
    
    public function addBannerAction(Request $request)
    {
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        
        $error_str = "";
        
        $opis = array(
            1 => "1. Баннер под поиском",
            2 => "2. Баннер на главной, между обьявами",
            3 => "3. Баннер на главной, под обьявами",
            4 => "4. Баннер в каталоге, справа",
            5 => "5. Баннер на главной, под меню Работы",
            6 => "6. Баннер на главной, в самом низу"
        );
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $form = $this->createFormBuilder();
        $form->add('title','text',array(
                'label' => 'Заголовок'
            ))
            ->add('descr','textarea', array(
                'label' => 'Описание',
                'required' => false
            ))
            ->add('url', 'text', array(
                'label' => 'Ссылка'
            ))
            ->add('position', 'choice', array(
                "choices" => $opis,
                'label' => 'Позиция'
            ));
        $form->add('upload', 'file', array(
                'label' => 'Файл баннера'
            ))
            ->add('save', 'submit', array(
                'label' => 'Сохранить'
            ));
        
        $form = $form->getForm();
        
        $form->handleRequest($request);
        
        if($form->isValid())
        {
            $data = $form->getData();
            
            $banner = new Banners();
            
            $file = new Document($data['upload'], 0 , $this->getDoctrine()->getManager());
            
            $parametrs = array();
            
            $parametrs['owner'] = 0;
            
            $parametrs['type'] = "banner";
                        
            $banner_name = $file->upload($parametrs);
            
            if($banner_name)
            {
                $banner->setTitle($data['title'])
                       ->setDescr($data['descr'])
                       ->setObjUrl($banner_name['file_name'])
                       ->setTime(new \DateTime("now"))
                       ->setType($banner_name['type'])
                       ->setUrl($data['url'])
                       ->setWidth($banner_name['x'])
                       ->setHeight($banner_name['y'])
                       ->setPosition($data['position'])
                       ;
                $em->persist($banner);
                $em->flush();
            }
            elseif(!$banner_name)
            {
                $error_str = "File uploading was failed!";
                echo $error_str;
            }
            //$em->persist($data);
            //$em->flush();
        }
        
        return $this->render('TorgovorotTorgBundle:Admin:item_cat.html.twig', array(
                'form' => $form->createView(),
                'error' => $error_str
            ));
    }
    
    public function editBannerAction($id, Request $request)
    {
        //check authorization
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        
        $error_str = "";
        
        $banner = new Banners();
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $banner = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Banners')->find($id);
        
        $opis = array(
            1 => "1. Баннер под поиском",
            2 => "2. Баннер на главной, между обьявами",
            3 => "3. Баннер на главной, под обьявами",
            4 => "4. Баннер в каталоге, справа",
            5 => "5. Баннер на главной, под меню Работы"
        );
        
        if($banner != null)
        {
            $form = $this->createFormBuilder();
            
            
            $form->add('title','text')
                 ->add('descr','textarea', array(
                     'reuqired' => false
                 ))
                 ->add('idd','hidden')
                 ->add('link','text')
                 ->add('position','choice', array(
                    "choices" => $opis,
                    'label' => 'Позиция',
                    'data' => $banner->getPosition()
                 ))
                 ->add('file','file',array(
                     'required' => false
                 ))
                 ->add('save','submit', array(
                     'label' => 'Обновить'
                 ))
                 ;
            $form = $form->getForm();
            
            $form->handleRequest($request);
        
            if($form->isValid())
            {
                $data = $form->getData();
                
                $banner_update = new Banners();
                
                $banner_update = $em->getRepository('TorgovorotTorgBundle:Banners')->find($data['idd']);
                                               
                $banner_update->setTitle($data['title'])
                              ->setDescr($data['descr'])
                              ->setUrl($data['link'])
                              ->setPosition($data['position'])
                        ;
                
                if($data['file'])
                {
                    
                    $file = new Document($data['file'], 0 , $em);
            
                    $parametrs = array();
            
                    $parametrs['owner'] = 0;
            
                    $parametrs['type'] = "banner";
                        
                    $banner_name = $file->upload($parametrs);
                    
                    $banner_update->setObjUrl($banner_name['file_name'])
                                  ->setWidth($banner_name['x'])
                                  ->setHeight($banner_name['y'])
                                  ->setType($banner_name['type'])
                                  ;
                }
                
                $em->persist($banner_update);
                $em->flush();
            }
        }
        return $this->render('TorgovorotTorgBundle:Admin:item_banner.html.twig', array(
                'form' => $form->createView(),
                'error' => $error_str,
                'item' => $banner
            ));
    }
    
    public function profileListAction(Request $request = null)
    {
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        $users = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Users")->findAll();
        
        $array = $users;
        
        $offset = $request->get('page') ? $request->get('page') : 1; //current page
        $limit = $request->get('onpage') ? $request->get('onpage') : 20; //current count on page
        $midrange = 7;
        $itemsCount = count($array); // item count
        
        $pagination = new Paginator($itemsCount, $offset , $limit, $midrange);
        
        $array = $this->getList($offset, $array, $limit); 
        
        return $this->render(
            'TorgovorotTorgBundle:Admin:items_profile.html.twig',
            array(
                // last username entered by the user
                'items' => $array,
                'paginator' => $pagination
                
            )
        );
    }
    
    public function catListAction(Request $request)
    {
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        
        $users = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Cats")->findAll();
        
        $array = $users;
        
        $offset = $request->get('page') ? $request->get('page') : 1; //current page
        $limit = $request->get('onpage') ? $request->get('onpage') : 20; //current count on page
        $midrange = 7;
        $itemsCount = count($array); // item count
        
        $pagination = new Paginator($itemsCount, $offset , $limit, $midrange);
        
        $array = $this->getList($offset, $array, $limit); 
        
        return $this->render(
            'TorgovorotTorgBundle:Admin:items_cats.html.twig',
            array(
                // last username entered by the user
                'items' => $array,
                'paginator' => $pagination
                
            )
        );
    }
    
    public function updateCatAction($id, Request $request)
    {
        $cat = new Cats();
        
        $cat = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Cats')->find($id);
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $form = $this->createForm(new CatsType(), $cat);
        
        $form->add('save','submit');
        
        $form->handleRequest($request);
        
        if($form->isValid())
        {
            $data = $form->getData();
            
            $em->persist($data);
            
            $em->flush();
        }
        
        return $this->render('TorgovorotTorgBundle:Admin:item_cat.html.twig', array(
                'form' => $form->createView(),
                'item' => $cat
            ));
    }
    
    /*
     * List of help function
     * will be temporary cause of no exist of help class
     * 
     */
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
    
    
    /*
     * Help functions to get infos created by users
     */
    private function getAddressesByUserId($id)
    {
        $array = array();
        
        $ids = explode(';', $id);
        
        if(count($ids) > 0)
        {
            foreach($ids as $aid)
            {
                $addr = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Address")->find($aid);
                if($addr != null)
                {
                    $array[] = $addr;
                }
            }
            
        }
        
        return $array;
    }
    //vacancies
    private function getVacanciesByUserId($id)
    {
        $vacancies = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsVacance')->findBy(array('ownerId' => $id));
        
        if($vacancies)
        {
            return $vacancies;
        }
        else return array();
    }
    
    //resumes
    private function getResumesByUserId($id)
    {
        $resumes = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsResume')->findBy(array('ownerId' => $id));
        
        if($resumes)
        {
            return $resumes;
        }
        else return array();
    }
    
    //goods
    private function getGoodsByUserId($id)
    {
        $goods = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsGoods')->findBy(array('ownerId' => $id));
        
        if($goods)
        {
            return $goods;
        }
        else return array();
    }
    
    //realties
    private function getRealtiesByUserId($id)
    {
        $object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsRealty')->findBy(array('ownerId' => $id));
        
        if($object)
        {
            return $object;
        }
        else return array();
    }
    
    private function getImagesById($ids)
    {
        $id_arr = explode(";", $ids);
        
        $array = array();
        
        if(count($id_arr) > 0)
        {
            foreach($id_arr as $id)
            {
                if($id != "")
                {
                    $image = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdvImages')->find($id);
                    
                    if($image != null)
                    {
                        $array[] = $image;
                    }
                }
            }
        }
        
        return $array;
    }
    
    /*
     * end of help info functions
     */
    
    private function checkIfIsAdmin()
    {
        
        if($this->get('security.context')->getToken()->getUser() != "anon.")
        {
            $user = $this->get('security.context')->getToken()->getUser();
            if($user->getAccessLvl() == 100)
            {
                
                return true;
            }
            else
            {
                
                return $this->redirect($this->generateUrl('torgovorot_torg_homepage'));
            }
        }
        else
        {
            return $this->redirect($this->generateUrl('torgovorot_torg_homepage'));
        }
        
    }
    
    
    public function saveNewFlat($array, $param = 0)
    {
        $em = $this->getDoctrine()->getEntityManager();
        //$user = $this->get('security.context')->getToken()->getUser();
        
        //$user_id = $user->getId();
        
        
        //adding address
        $addr = new Address();
        
        $addr = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Address')->find($array['addr_id']);
        
        $addr->setCity($array['city']);
        $addr->setFax("");
        $addr->setPhone("");
        $addr->setHints($array['hints']);
        $addr->setHouse($array['house']);
        $addr->setIndexN($array['index']);
        $addr->setName($array['name']);
        $addr->setOffice($array['flat']);
        //$addr->setOwnerId($user_id);
        $addr->setStreet($array['street']);
        
        $em->persist($addr);
        $em->flush();
        
        $addr_id = $addr->getId();
        
        //adding characteristics
        $ch_a = new ChApartment();
        
        $ch_a = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:ChApartment')->find($array['ch_id']);
        
        $ch_a->setRooms($array['rooms']);
        $ch_a->setLivingSquare($array['living_square']);
        $ch_a->setGeneralSquare($array['general_square']);
        $ch_a->setFloor($array['floor']);
        $ch_a->setFloorCount($array['floor_count']);
        $ch_a->setRtype(1);
        
        $conv_arr = $array['conv'];
        
        foreach($conv_arr as $key => $value)
        {
            switch($value)
            {
                case 0:
                    $ch_a->setBalcony(1);
                    break;
                case 1:
                    $ch_a->setRefregirator(1);
                    break;
                case 2:
                    $ch_a->setTv(1);
                    break;
                case 3:
                    $ch_a->setPhone(1);
                    break;
                case 4:
                    $ch_a->setRefregirator(1);
                    break;
                case 5:
                    $ch_a->setDishwasher(1);
                    break;
                case 6:
                    $ch_a->setWashingmachine(1);
                    break;
                case 7:
                    $ch_a->setBoiler(1);
                    break;
            }
        }
        $ch_a->setHouseType($array['house_mat']);
        $ch_a->setBathType($array['bath_type']);
        //saving video
        $video = new Videos();
        
        $video = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Videos')->find($array['video_id']);
        
        $video->setUrl($array['video']);
        $em->persist($video);
        $em->flush();
        
        $video_id = $video->getId();
        
        $ch_a->setVideoId($video_id);
        
        $em->persist($ch_a);
        $em->flush();
        $ch_id = $ch_a->getId();
        
        //finish adding flat ads
        
        $ads = new AdsRealty();
        
        $ads = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsRealty')->find($array['ads_id']);
        
        //$ads->setOwnerId($user_id);
        $ads->setTitle($array['title']);
        $ads->setDescription($array['description']);
        $ads->setShortDesc($array['shortDesc']);
        $ads->setPrice($array['price']);
        $ads->setTime(new \DateTime());
        $ads->setDays(7);
        $ads->setAddrId($addr_id);
        $ads->setChId($ch_id);
        $ads->setAdsType($param);
        
        if(isset($array['photo']))
        {
           $photos_ids = $ads->getPhotoIds();
           
           if(!empty($array['photo']))
           {
               foreach($array['photo'] as $photo_ent)
               {
                   if($photo_ent != "")
                   {
                        $pid = $this->savePhoto($photo_ent, array("type"=>"photo"));
                        if($pid)
                        {
                            $photos_ids .= $photos_ids == "" ? $pid : ";".$pid;
                        }
                   }
               }
           }
           $ads->setPhotoIds($photos_ids);
        }
        $em->persist($ads);
        $em->flush();
    }
    
    public function saveNewHouse($array, $param)
    {
        $em = $this->getDoctrine()->getEntityManager();
        //$user = $this->get('security.context')->getToken()->getUser();
        
        //$user_id = $user->getId();
        
        
        //adding address
        $addr = new Address();
        
        $addr = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Address')->find($array['addr_id']);
        
        $addr->setCity($array['city']);
        $addr->setFax("");
        $addr->setPhone("");
        $addr->setHints($array['hints']);
        $addr->setHouse($array['house']);
        $addr->setIndexN($array['index']);
        $addr->setName($array['name']);
        $addr->setOffice($array['flat']);
        //$addr->setOwnerId($user_id);
        $addr->setStreet($array['street']);
        
        $em->persist($addr);
        $em->flush();
        
        $addr_id = $addr->getId();
        
        
        $char = new ChHouse();
        
        $char = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:ChHouse')->find($array['ch_id']);
        
        $char->setHouseMaterial($array['house_mat']);
        $char->setHouseType($array['ttype']);
        $char->setPlaning($array['plan']);
        $char->setSquareEarth($array['esquare']);
        $char->setSquareHouse($array['hsquare']);
        
        $conv_arr = $array['conv'];
        
        
        foreach($conv_arr as $key => $value)
        {
            switch($value)
            {
                case 0:
                    $char->setConvinient(1);
                    break;
                case 1:
                    $char->setCanalization(1);
                    break;
                case 2:
                    $char->setWater(1);
                    break;
                case 3:
                    $char->setElectricity(1);
                    break;
                case 4:
                    $char->setGas(1);
                    break;
            }
        }        
        
        
        //saving video
        $video = new Videos();
        
        $video = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Videos')->find($array['video_id']);
        
        $video->setUrl($array['video']);
        $em->persist($video);
        $em->flush();
        
        $video_id = $video->getId();
        
        $char->setVideoId($video_id);
        
        $em->persist($char);
        $em->flush();
        $char_id = $char->getId();        
        //finish adding flat ads
        
        $ads = new AdsRealty();
        
        $ads = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsRealty')->find($array['ads_id']);
        
        //$ads->setOwnerId($user_id);
        $ads->setTitle($array['title']);
        $ads->setDescription($array['description']);
        $ads->setShortDesc($array['shortDesc']);
        $ads->setPrice($array['price']);
        $ads->setTime(new \DateTime());
        $ads->setDays(7);
        $ads->setAddrId($addr_id);
        $ads->setChId($char_id);
        $ads->setAdsType($param);
        $ads->setCategoryId(1);
        if($array['photo'])
        {
           $photos_ids = $ads->getPhotoIds();
           
           if(!empty($array['photo']))
           {
               foreach($array['photo'] as $photo_ent)
               {
                   if($photo_ent != "")
                   {
                        $pid = $this->savePhoto($photo_ent, array("type"=>"photo"));
                        if($pid)
                        {
                            $photos_ids .= $photos_ids == "" ? $pid : ";".$pid;
                        }
                   }
               }
           }
           $ads->setPhotoIds($photos_ids);
        }
        $em->persist($ads);
        $em->flush();        
        
    }
    
    public function saveNewRealty($array, $param)
    {
        $em = $this->getDoctrine()->getEntityManager();
        //$user = $this->get('security.context')->getToken()->getUser();
        
        //$user_id = $user->getId();
        
        
        //adding address
        $addr = new Address();
        
        
        $addr = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Address')->find($array['addr_id']);
        
        $addr->setCity($array['city']);
        $addr->setFax("");
        $addr->setPhone("");
        $addr->setHints($array['hints']);
        $addr->setHouse($array['house']);
        $addr->setIndexN($array['index']);
        $addr->setName($array['name']);
        $addr->setOffice($array['flat']);
        //$addr->setOwnerId($user_id);
        $addr->setStreet($array['street']);
        
        $em->persist($addr);
        $em->flush();
        
        $addr_id = $addr->getId();
        
        
        
        $ch = new ChComm();
        
        $ch = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:ChComm')->find($array['ch_id']);
        
        $ch->setCommType($array['type']);
        $ch->setSquareEarth($array['esquare']);
        $ch->setSquarePlace($array['hsquare']);
        
        $em->persist($ch);
        $em->flush();        
        
        $ch_id = $ch->getId();
        //saving video
        $video = new Videos();
        
        $video = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Videos')->find($array['video_id']);
        
        $video->setUrl($array['video']);
        $em->persist($video);
        $em->flush();
        
        $video_id = $video->getId();
        
        $ch->setVideoId($video_id);
        
        $ads = new AdsRealty();
        
        $ads = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsRealty')->find($array['ads_id']);
        
        //$ads->setOwnerId($user_id);
        $ads->setTitle($array['title']);
        $ads->setDescription($array['description']);
        $ads->setShortDesc($array['shortDesc']);
        $ads->setPrice($array['price']);
        $ads->setTime(new \DateTime());
        $ads->setDays(7);
        $ads->setAddrId($addr_id);
        $ads->setChId($ch_id);
        $ads->setAdsType($param);
        $ads->setCategoryId(1);
        if($array['photo'])
        {
           $photos_ids = $ads->getPhotoIds();
           
           if(!empty($array['photo']))
           {
               foreach($array['photo'] as $photo_ent)
               {
                   if($photo_ent != "")
                   {
                        $pid = $this->savePhoto($photo_ent, array("type"=>"photo"));
                        if($pid)
                        {
                            $photos_ids .= $photos_ids == "" ? $pid : ";".$pid;
                        }
                   }
               }
           }
           $ads->setPhotoIds($photos_ids);
        }
        $em->persist($ads);
        $em->flush();        
    }
    
    public function saveNewGarage($array, $param)
    {
        $em = $this->getDoctrine()->getEntityManager();
        //$user = $this->get('security.context')->getToken()->getUser();
        
        //$user_id = $user->getId();
        
        
        //adding address
        $addr = new Address();
        
        $ads = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Address')->find($array['addr_id']);
        
        $addr->setCity($array['city']);
        $addr->setFax("");
        $addr->setPhone("");
        $addr->setHints($array['hints']);
        $addr->setHouse($array['house']);
        $addr->setIndexN($array['index']);
        $addr->setName($array['name']);
        $addr->setOffice($array['flat']);
        //$addr->setOwnerId($user_id);
        $addr->setStreet($array['street']);
        
        $em->persist($addr);
        $em->flush();
        
        $addr_id = $addr->getId();
        
        $ch = new ChGarage();
        
        $ch = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:ChGarage')->find($array['ch_id']);
        
        $ch->setGarageType($array['type']);
        
        $conv_arr = $array['conv'];
        
        foreach($conv_arr as $key => $value)
        {
            switch($value)
            {
                case 0:
                    $ch->setConvinient(1);
                    break;
                case 1:
                    $ch->setElectricity(1);
                    break;
                case 2:
                    $ch->setWater(1);
                    break;
                case 3:
                    $ch->setKesson(1);
                    break;
                case 4:
                    $ch->setObservationPit(1);
                    break;
            }
        }
        
        
        
        $em->persist($ch);
        $em->flush();        
        
        $ch_id = $ch->getId();
        
        //saving video
        $video = new Videos();
        
        $video = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:Videos')->find($array['video_id']);
        
        $video->setUrl($array['video']);
        $em->persist($video);
        $em->flush();
        
        $video_id = $video->getId();
        
        $ch->setVideoId($video_id);
        
        $ads = new AdsRealty();
        
        $ads = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsRealty')->find($array['ads_id']);
        
        //$ads->setOwnerId($user_id);
        $ads->setTitle($array['title']);
        $ads->setDescription($array['description']);
        $ads->setShortDesc($array['shortDesc']);
        $ads->setPrice($array['price']);
        $ads->setTime(new \DateTime());
        $ads->setDays(7);
        $ads->setAddrId($addr_id);
        $ads->setChId($ch_id);
        $ads->setAdsType($param);
        $ads->setCategoryId(1);
        
        if($array['photo'])
        {
           $photos_ids = $ads->getPhotoIds();
           
           if(!empty($array['photo']))
           {
               foreach($array['photo'] as $photo_ent)
               {
                   if($photo_ent != "")
                   {
                        $pid = $this->savePhoto($photo_ent, array("type"=>"photo"));
                        if($pid)
                        {
                            $photos_ids .= $photos_ids == "" ? $pid : ";".$pid;
                        }
                   }
               }
           }
           $ads->setPhotoIds($photos_ids);
        }
        
        $em->persist($ads);
        $em->flush();
    }
    
    public function pagesAction(Request $request = null)
    {
        //$items = array();
        
        $page = $request->get('page') ? $request->get('page') : 1; //current page
        
        $onpage = $request->get('onpage') ? $request->get('onpage') : 10; //on page
        
        $limit = ($page-1)+$onpage;
        
        $count = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Pages")->findAll();

        $query = $this->getDoctrine()->getManager()->createQuery(
                    "SELECT p
                    FROM TorgovorotTorgBundle:Pages p
                    ORDER BY p.mainPage DESC
                    "
                );
        
        $count = $query->getResult();
        
        $items = $this->getList($page, $count, $onpage);
        
        $pagination = new Paginator(count($count), $page , $limit, 7);
        
        return $this->render(
            'TorgovorotTorgBundle:Admin:items_pages.html.twig',
            array(
                // last username entered by the user
                'items' => $items,
                'paginator' => $pagination
                
            )
        );
    }
    
    public function pageAction($id = 0, Request $request = null)
    {
        $item = new Pages();
        
        $item = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Pages")->find($id);
        
        $form = $this->createFormBuilder();
            
        $topMenu = false;
        $botMenu = false;
        if($item->getTopMenu() == 1)
        {
            $topMenu = true;
        }
        if($item->getBottomMenu() == 1)
        {
            $botMenu = true;
        }
            if($item->getUndeleted() != 1)
            {
               $form->add('content','textarea',array(
                   'required' => false
               ))
                    ->add('chpu','text');                
            }

            $form->add('title','text')
                 ->add('idd','hidden')
                 ->add('top_menu', 'checkbox',array(
                     'label' => "Отображать в шапке?",
                     'value' => 1,
                     'data' => $topMenu,
                     'required' => false
                 ))
                 ->add('bot_menu', 'checkbox',array(
                     'label' => "Отображать в нижнем меню?",
                     'value' => 1,
                     'data' => $botMenu,
                     'required' => false
                 ))
                 ->add('save','submit', array(
                     'label' => 'Обновить'
                 ))
                 ;
            $form = $form->getForm();
            
            $form->handleRequest($request);
        
            if($form->isValid())
            {
                $data = $form->getData();
                
                $new_item = new Pages();
                
                $new_item = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Pages")->find($data['idd']);
                
                $new_item->setTitle($data['title']);
                $new_item->setContent($data['content']);
                if($data['chpu'] != "")
                {
                    $new_item->setChpu($data['chpu']);
                }
                else
                {
                    $new_item->setChpu($this->proc->translit($data['title']));
                }
                if($data['top_menu'] == 1)
                {
                    $new_item->setTopMenu(1);
                }
                else
                {
                    $new_item->setTopMenu(0);
                }
                if($data['bot_menu'] == 1)
                {
                    $new_item->setBottomMenu(1);
                }
                else
                {
                    $new_item->setBottomMenu(0);
                }
                
                $this->getDoctrine()->getEntityManager()->persist($new_item);
                $this->getDoctrine()->getEntityManager()->flush();
                
                $item = $new_item;
            }
        
        return $this->render(
            'TorgovorotTorgBundle:Admin:item_page.html.twig',
            array(
                // last username entered by the user
                'item' => $item,
                'form' => $form->createView()
            )
        );
    }
    
    public function newPageAction(Request $request = null)
    {
        $form = $this->createFormBuilder();
        $item = new Pages();    
        
            $this->proc = new ProcessItem($this->getDoctrine()->getEntityManager());
            $form->add('title','text')
                 ->add('content','textarea',array(
                     'required' => false
                 ))
                 ->add('idd','hidden', array(
                     'required' => false
                 ))
                 ->add('chpu','text', array(
                     'required' => false
                 ))
                 ->add('top_menu', 'checkbox',array(
                     'label' => "Отображать в шапке?",
                     'value' => 1,
                     'required' => false
                 ))
                 ->add('bot_menu', 'checkbox',array(
                     'label' => "Отображать в нижнем меню?",
                     'value' => 1,
                     'required' => false
                 ))
                 ->add('save','submit', array(
                     'label' => 'Сохранить'
                 ))
                 ;
            $form = $form->getForm();
            
            $form->handleRequest($request);
        
            if($form->isValid())
            {
                $data = $form->getData();
                
                $new_item = new Pages();
                
                $new_item->setTitle($data['title']);
                $new_item->setContent($data['content']);
                if($data['chpu'] != "")
                {
                    $new_item->setChpu($data['chpu']);
                }
                else
                {
                    $new_item->setChpu($this->proc->translit($data['title']));
                }
                if($data['top_menu'] == 1)
                {
                    $new_item->setTopMenu(1);
                }
                else
                {
                    $new_item->setTopMenu(0);
                }
                if($data['bot_menu'] == 1)
                {
                    $new_item->setBottomMenu(1);
                }
                else
                {
                    $new_item->setBottomMenu(0);
                }
                
                $this->getDoctrine()->getEntityManager()->persist($new_item);
                $this->getDoctrine()->getEntityManager()->flush();
            }
            
            return $this->render(
            'TorgovorotTorgBundle:Admin:item_page.html.twig',
            array(
                // last username entered by the user
                'item' => $item,
                'form' => $form->createView(),
                'type' => "add"
            )
        );
    }
    
    public function realtiesAction(Request $request = null)
    {
        $page = $request->get('page') ? $request->get('page') : 1; //current page
        
        $onpage = $request->get('onpage') ? $request->get('onpage') : 10; //on page
        
        $limit = ($page-1)+$onpage;
        
        $count = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:AdsRealty")->findAll();

        $items = $this->getList($page, $count, $onpage);
        
        $pagination = new Paginator(count($count), $page , $limit, 7);
        
        return $this->render(
            'TorgovorotTorgBundle:Admin:items_all.html.twig',
            array(
                // last username entered by the user
                'items' => $items,
                'paginator' => $pagination,
                'type' => 1
            )
        );
    }
    
    public function vacancesAction(Request $request = null)
    {
        $page = $request->get('page') ? $request->get('page') : 1; //current page
        
        $onpage = $request->get('onpage') ? $request->get('onpage') : 10; //on page
        
        $limit = ($page-1)+$onpage;
        
        $count = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:AdsVacance")->findAll();

        $items = $this->getList($page, $count, $onpage);
        
        $pagination = new Paginator(count($count), $page , $limit, 7);
        
        return $this->render(
            'TorgovorotTorgBundle:Admin:items_all.html.twig',
            array(
                // last username entered by the user
                'items' => $items,
                'paginator' => $pagination,
                'type' => 2
                
            )
        );
    }
    
    public function carsAction(Request $request = null)
    {
        $page = $request->get('page') ? $request->get('page') : 1; //current page
        
        $onpage = $request->get('onpage') ? $request->get('onpage') : 10; //on page
        
        $limit = ($page-1)+$onpage;
        
        $count = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:AdsCars")->findAll();

        $items = $this->getList($page, $count, $onpage);
        
        $pagination = new Paginator(count($count), $page , $limit, 7);
        
        return $this->render(
            'TorgovorotTorgBundle:Admin:items_all.html.twig',
            array(
                // last username entered by the user
                'items' => $items,
                'paginator' => $pagination,
                'type' => 8
                
            )
        );
    }
    
    public function resumesAction(Request $request = null)
    {
        $page = $request->get('page') ? $request->get('page') : 1; //current page
        
        $onpage = $request->get('onpage') ? $request->get('onpage') : 10; //on page
        
        $limit = ($page-1)+$onpage;
        
        $count = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:AdsResume")->findAll();

        $items = $this->getList($page, $count, $onpage);
        
        $pagination = new Paginator(count($count), $page , $limit, 7);
        
        return $this->render(
            'TorgovorotTorgBundle:Admin:items_all.html.twig',
            array(
                // last username entered by the user
                'items' => $items,
                'paginator' => $pagination,
                'type' => 3
                
            )
        );
    }
    
    public function goodsAction(Request $request = null)
    {
        $page = $request->get('page') ? $request->get('page') : 1; //current page
        
        $onpage = $request->get('onpage') ? $request->get('onpage') : 10; //on page
        
        $limit = ($page-1)+$onpage;
        
        $count = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:AdsGoods")->findAll();

        $items = $this->getList($page, $count, $onpage);
        
        $pagination = new Paginator(count($count), $page , $limit, 7);
        
        return $this->render(
            'TorgovorotTorgBundle:Admin:items_all.html.twig',
            array(
                // last username entered by the user
                'items' => $items,
                'paginator' => $pagination,
                'type' => 4
                
            )
        );
    }
    
    public function discountsAction(Request $request = null)
    {
        $page = $request->get('page') ? $request->get('page') : 1; //current page
        
        $onpage = $request->get('onpage') ? $request->get('onpage') : 10; //on page
        
        $limit = ($page-1)+$onpage;
        
        $count = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:AdsDiscounts")->findAll();

        $items = $this->getList($page, $count, $onpage);
        
        $pagination = new Paginator(count($count), $page , $limit, 7);
        
        return $this->render(
            'TorgovorotTorgBundle:Admin:items_all.html.twig',
            array(
                // last username entered by the user
                'items' => $items,
                'paginator' => $pagination,
                'type' => 6
                
            )
        );
    }
    
    public function eventsAction(Request $request = null)
    {
        $page = $request->get('page') ? $request->get('page') : 1; //current page
        
        $onpage = $request->get('onpage') ? $request->get('onpage') : 10; //on page
        
        $limit = ($page-1)+$onpage;
        
        $count = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:AdsEvents")->findAll();

        $items = $this->getList($page, $count, $onpage);
        
        $pagination = new Paginator(count($count), $page , $limit, 7);
        
        return $this->render(
            'TorgovorotTorgBundle:Admin:items_all.html.twig',
            array(
                // last username entered by the user
                'items' => $items,
                'paginator' => $pagination,
                'type' => 5
                
            )
        );
    }
    
    public function updateEventsAction($id,$id1, Request $request)
    {
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        $user = new Users();
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $user = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Users")->find($id);
        
        $object = new AdsEvents();
        
        $object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsEvents')->find($id1);
        
        $form = $this->createForm(new AdsEventsType($em),$object);
        
        $form->add('save','submit', array(
            'label' => 'Сохранить'
        ));
        
        
        
        $form->handleRequest($request);
        
        if($form->isValid())
        {
            $data = $form->getData();
            $file = $form->get("photo")->getData();
            if($file != null)
                $data->setPhotoIds($this->savePhoto($file, array("type"=>"photo")));
            
            $em->persist($data);
            $em->flush();
        }
        
        return $this->render('TorgovorotTorgBundle:Admin:item_event.html.twig', array(
                'form' => $form->createView(),
                'user' => $user,
                'form_type' => 'event',
                'time' => '$time',
                'item' => $object
            ));
    }
    
    public function updateDiscountsAction($id,$id1, Request $request)
    {
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        $user = new Users();
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $user = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Users")->find($id);
        
        $object = new AdsDiscounts();
        
        $object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsDiscounts')->find($id1);
        
        $form = $this->createForm(new AdsDiscountsType($em),$object);
        
        
        $form->add('save','submit', array(
            'label' => 'Сохранить'
        ));
        
        
        $form->handleRequest($request);
        
        if($form->isValid())
        {
            $data = $form->getData();
            $em->persist($data);
            $em->flush();
        }
        
        return $this->render('TorgovorotTorgBundle:Admin:item_addr_profile.html.twig', array(
                'form' => $form->createView(),
                'user' => $user,
                'form_type' => 'disc',
                'time' => '$time'
            ));
    }
    
    public function catsAction(Request $request)
    {
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        
        //$users = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Cats")->findAll();
        
        $query = $this->getDoctrine()->getManager()->createQuery(
                    "SELECT p
                    FROM TorgovorotTorgBundle:Cats p
                    ORDER BY p.topPos DESC
                    "
                );
        $users = $query->getResult();
        
        $array = $users;
        
        $offset = $request->get('page') ? $request->get('page') : 1; //current page
        $limit = $request->get('onpage') ? $request->get('onpage') : 10; //current count on page
        $midrange = 7;
        $itemsCount = count($array); // item count
        
        $pagination = new Paginator($itemsCount, $offset , $limit, $midrange);
        
        $array = $this->getList($offset, $array, $limit); 
        
        return $this->render(
            'TorgovorotTorgBundle:Admin:items_cats.html.twig',
            array(
                // last username entered by the user
                'items' => $array,
                'paginator' => $pagination
                
            )
        );
    }
    
    public function complaintsAction(Request $request)
    {
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        
        //$users = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Cats")->findAll();
        
        $query = $this->getDoctrine()->getManager()->createQuery(
                    "SELECT p
                    FROM TorgovorotTorgBundle:Complaints p
                    ORDER BY p.time DESC
                    "
                );
        $users = $query->getResult();
        
        $em = $this->getDoctrine()->getManager();
        
        $array = $users;
        
        $offset = $request->get('page') ? $request->get('page') : 1; //current page
        $limit = $request->get('onpage') ? $request->get('onpage') : 10; //current count on page
        $midrange = 7;
        $itemsCount = count($array); // item count
        
        $pagination = new Paginator($itemsCount, $offset , $limit, $midrange);
        
        $array = $this->getList($offset, $array, $limit); 
        
        $item_arr = array(
            'id' => 0,
            'item_title' => '',
            'category' => '',
            'ip' => '',
            'date' => ''
        );
        
        $new_arr = array();
        
        foreach($array as $object)
        {
            $id = $object->getId();
            $ads_type = $object->getAdsType();
            $item_id = $object->getItemId();
            $ip = $object->getIp();
            $date = $object->getTime();
            
            //
            $item_arr['id'] = $id;
            $item_arr['ip'] = $ip;
            $item_arr['date'] = $date;
            //
            
            $class = "";
            $rus_class = "";
            switch($ads_type)
            {
                case 1:
                    $class = "AdsRealty";
                    $rus_class = "Недвижимость";
                    break;
                case 2:
                    $class = "AdsVacance";
                    $rus_class = "Вакансия";
                    break;
                case 3:
                    $class = "AdsResume";
                    $rus_class = "Резюме";
                    break;
                case 4:
                    $class = "AdsGoods";
                    $rus_class = "Товары";
                    break;
                case 5:
                    $class = "AdsEvents";
                    $rus_class = "Событие";
                    break;
                case 6:
                    $class = "AdsDiscounts";
                    $rus_class = "Скидки";
                    break;
                case 7:
                    $class = "AdsRealty";
                    $rus_class = "test";
                    break;
                case 8:
                    $class = "AdsCars";
                    $rus_class = "Авто";
                    break;
            }
            
            $item_arr['category'] = $rus_class;
            
            if($class != "")
            {
                
                $item = $em->getRepository("TorgovorotTorgBundle:$class")->find($item_id);
                if($item != null)
                {
                    
                    //$new_arr[] = $item_arr;
                    $item_arr['item_title'] = $item->getTitle();
                }
                else
                {
                    $item_arr['item_title'] = "не существует";
                }
            }
            $new_arr[] = $item_arr;
        }
        //echo "<pre>";
        //print_r($new_arr);
        //echo "</pre>";
        return $this->render(
            'TorgovorotTorgBundle:Admin:items_compl.html.twig',
            array(
                // last username entered by the user
                'items' => $array,
                'items_new' => $new_arr,
                'paginator' => $pagination
                
            )
        );
    }
    
    public function executeCatAction($id = 0, Request $request)
    {
        
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        
        $entity = new Cats();
        
        if($id != 0)
        {
            $entity = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Cats")->find($id);
        }
        
        $cat_bind = new CatBind();
        
        $cat_bind = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:CatBind")->findOneBy(array("podId" => $id));
        
        $mainId = 0;
        
        if($id != 0 and $cat_bind != null)
        {
            $mainId = $cat_bind->getMainId();
        }
        
        //get all registred cats with pod cats
        $all_cats = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Cats")->findAll();
        
        $arr_cat = array("0" => "Нет");
        
        foreach($all_cats as $cat_entity)
        {
            $cat = new Cats();
            $cat = $cat_entity;
            
            $one_bind = new CatBind();
            $one_bind = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:CatBind")->findBy(array("mainId" => $cat->getId()));
            
            if($one_bind != null)
            {
                $new_arr = array();
                foreach($one_bind as $bind)
                {
                    $pod = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Cats")->find($bind->getPodId());
                    if($pod != null)
                    {
                        $new_arr[$pod->getId()] = $pod->getName();
                    }
                    
                }
                $arr_cat[$cat->getName()] = $new_arr;
            }
        }
        //
        
        $form = $this->createFormBuilder();
        
        $form->add("title", "text", array(
                "data" => $entity->getName(),
                "label" => "Название"
             ))
             ->add("cats", "choice", array(
                 "choices" => $this->createSelectCats(),
                 "label" => "Категории",
                 "data" => $mainId
             ))
             ->add("pos", "text", array(
                "data" => $entity->getTopPos(),
                "label" => "Позиция"
             ))
             ->add("save", "submit",array(
                 "label" => "Сохранить"
             ));
        
        $form = $form->getForm();
        
        $form->handleRequest($request);
        
        if($form->isValid())
        {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();

            //cats = $request->get("cats");
            
            $cats = $data['cats'];
            
            $entity->setName($data['title']);
            $entity->setTopPos($data['pos']);
            
            if($id != 0)
            {
                $main_bind = $em->getRepository("TorgovorotTorgBundle:CatBind")->findBy(array("mainId" => $id));
                $pod_bind = $em->getRepository("TorgovorotTorgBundle:CatBind")->findBy(array("podId" => $id));
                
                /*echo "<pre>";
                print_r($pod_bind);
                echo "</pre>";*/
                
                if($pod_bind != null and !empty($pod_bind))
                {
                    foreach($pod_bind as $pod_ent)
                    {
                        $em->remove($pod_ent);
                        $em->flush();
                    }
                    
                }
                
                if($main_bind != null and !empty($pod_bind))
                {
                    $em->remove($main_bind);
                    $em->flush();
                }
                
                if($cats != 0)
                {
                    $new_bind = new CatBind();
                    $new_bind->setMainId($cats);
                    $new_bind->setPodId($id);
                    $em->persist($new_bind);
                    $em->flush();
                }
            }
            else
            {
                if($cats != 0)
                {
                    $new_bind = new CatBind();
                    $new_bind->setMainId($cats);
                    $new_bind->setPodId($id);
                    $em->persist($new_bind);
                    $em->flush();
                }
            }
            
            $em->persist($entity);
            $em->flush();
        }
        
        return $this->render('TorgovorotTorgBundle:Admin:item_cat.html.twig', array(
                'form' => $form->createView()
               ));
    }
    
    private function savePhoto($file, $param = array())
    {
        $photo = new Document($file, 0, $this->getDoctrine()->getEntityManager());
        
        
        
        return $photo->upload($param);
    }
    
    private function createSelectCats()
    {
        
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        
        $em = $this->getDoctrine()->getManager();
        
        $cat = new Cats();
        
        $entity = $em->getRepository("TorgovorotTorgBundle:Cats")->findAll();
        
        $array = array(0 => "-Нет-");
        
        $end = false;
        
        //while(!empty($entity))
        //{
            foreach($entity as $cat)
            {
                $temp_arr = array();
                
                $bind = new CatBind();
                
                $bind = $em->getRepository("TorgovorotTorgBundle:CatBind")->findOneBy(array("podId" => $cat->getId()));
                
                if($bind != null)
                    continue;
                else
                {
                    $next_bind = $em->getRepository("TorgovorotTorgBundle:CatBind")->findBy(array("mainId" => $cat->getId()));
                    if($next_bind != null)
                    {
                        /*foreach($next_bind as $one_bind)
                        {
                            $catty = new Cats();
                            $catty = $em->getRepository("TorgovorotTorgBundle:Cats")->find($one_bind->getPodId());
                            
                            $temp_arr[$catty->getId()] = $catty->getName();
                        }*/
                        $array[$cat->getId()] = $cat->getName();
                    }
                }
            }
        //}
        return $array;
    }
    
    /*
     * Characteristic controller
     */
    
    public function getLabelsAction(Request $request = null)
    {
        
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        
        $labels = new Labels();
        
        $page = $request->get("page") ? $request->get("page") : 1;
        $onpage = $request->get("onpage") ? $request->get("onpage") : 10;
        
        $start = ($page-1)*$onpage;
        $end = $page * $onpage;
        
        $limit_str = "LIMIT $start, $onpage";
        
        $all = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Labels")->findAll();
        
        $query = $this->getDoctrine()->getManager()->createQuery(
                    "SELECT p
                    FROM TorgovorotTorgBundle:Labels p
                    "
                );
        $query->setMaxResults($onpage);
        $query->setFirstResult($start);
        
        $labels = $query->getResult();
        
        $pagination = new Paginator(count($all), $page , $onpage, 7);
        
        return $this->render(
                "TorgovorotTorgBundle:Admin:items_all.html.twig",
                array(
                    "items" => $labels,
                    "paginator" => $pagination,
                    "type" => 7
                ));
    }
    
    public function labelAction($id = 0, Request $request = null)
    {
        
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        
        $label = new Labels();
        
        if($id != 0 and $id > 0)
        {
            $label = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Labels")->find($id);
        }
        
        $form = $this->createForm(new LabelsType(), $label);
        
        $form->handleRequest($request);
        
        if($form->isValid())
        {
            $data = $form->getData();
            
            $this->getDoctrine()->getManager()->persist($data);
            $this->getDoctrine()->getManager()->flush();
        }
        
        return $this->render("TorgovorotTorgBundle:Admin:item_addr_profile.html.twig", array(
                "form" => $form->createView()
            ));
    }
    
    public function getCharsAction(Request $request = null)
    {
        
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        
        $chars = new Labels();
        
        $page = $request->get("page") ? $request->get("page") : 1;
        $onpage = $request->get("onpage") ? $request->get("onpage") : 10;
        
        $start = ($page-1)*$onpage;
        $end = $page * $onpage;
        
        $limit_str = "LIMIT $start, $onpage";
        
        $all = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Characteristics")->findAll();
        
        $query = $this->getDoctrine()->getManager()->createQuery(
                    "SELECT p
                    FROM TorgovorotTorgBundle:Characteristics p
                    "
                );
        $query->setMaxResults($onpage);
        $query->setFirstResult($start);
        
        $chars = $query->getResult();
        
        $pagination = new Paginator(count($all), $page , $onpage, 7);
        
        return $this->render(
                "TorgovorotTorgBundle:Admin:items_all.html.twig",
                array(
                    "items" => $chars,
                    "paginator" => $pagination,
                    "type" => 10
                ));
    }
    
    public function charAction($id = 0, Request $request = null)
    {
        
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        
        $char = new Characteristics();
        
        $char_bind = new CharacterBind();
        
        $ads_type = array(
            //"1" => "Недвижимость", 
            //"2" => "Вакансии", 
            //"3" => "Резюме", 
            //"4" => "Товары",
            //"5" => "Афиша",
            //"6" => "Скидки",
            "7" => "Автомобили");
        
        if($id != 0 and $id > 0)
        {
            $char = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Characteristics")->find($id);
            
            $char_bind = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:CharacterBind")->findOneBy(array("characterId" => $char->getId()));
        }
        
        $labels = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Labels")->findAll();
        $lab_arr = array();
        foreach ($labels as $label)
        {
            $lab_arr[$label->getId()] = $label->getName();
        }
        
        $form = $this->createForm(new CharacteristicsType(), $char);
        
        $form->add("label_id", "choice", array(
                "label" => "Выбрать категорию характеристики",
                "choices" => $lab_arr,
                "data" => $char_bind->getLabelId(),
                "mapped" => false
             ))
             ->add("adsId", "choice", array(
                "label" => "Тип обьявления",
                "mapped" => false,
                "choices" => $ads_type,
                "data" => $char_bind->getAdsType()
             ))
             ->add("save", "submit", array(
                "label" => "Сохранить"
             ));
        
        
        $form->handleRequest($request);
        
        if($form->isValid())
        {
            $data = $form->getData();
            
            $label_id = $form->get("label_id")->getData();
            $adsId = $form->get("adsId")->getData();
            
            $this->getDoctrine()->getManager()->persist($data);
            $this->getDoctrine()->getManager()->flush();
            
            
            $char_bind->setAdsType($adsId);
            $char_bind->setLabelId($label_id);
            $char_bind->setCharacterId($char->getId());
            
            $this->getDoctrine()->getManager()->persist($char_bind);
            $this->getDoctrine()->getManager()->flush();
        }
        
        return $this->render("TorgovorotTorgBundle:Admin:item_addr_profile.html.twig", array(
                "form" => $form->createView()
            ));
    }
    
    public function getMarksAction(Request $request = null)
    {
        
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        
        $labels = new CarsMark();
        
        $page = $request->get("page") ? $request->get("page") : 1;
        $onpage = $request->get("onpage") ? $request->get("onpage") : 10;
        
        $start = ($page-1)*$onpage;
        $end = $page * $onpage;
        
        $limit_str = "LIMIT $start, $onpage";
        
        $all = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:CarsMark")->findAll();
        
        $query = $this->getDoctrine()->getManager()->createQuery(
                    "SELECT p
                    FROM TorgovorotTorgBundle:CarsMark p
                    "
                );
        $query->setMaxResults($onpage);
        $query->setFirstResult($start);
        
        $labels = $query->getResult();
        
        $pagination = new Paginator(count($all), $page , $onpage, 7);
        
        return $this->render(
                "TorgovorotTorgBundle:Admin:items_all.html.twig",
                array(
                    "items" => $labels,
                    "paginator" => $pagination,
                    "type" => 9
                ));
    }
    
    public function markAction($id = 0, Request $request = null)
    {
        
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        
        $label = new CarsMark();
        
        if($id != 0 and $id > 0)
        {
            $label = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:CarsMark")->find($id);
        }
        
        $form = $this->createForm(new CarsMarkType(), $label);
        
        $form->handleRequest($request);
        
        if($form->isValid())
        {
            $data = $form->getData();
            
            $this->getDoctrine()->getManager()->persist($data);
            $this->getDoctrine()->getManager()->flush();
        }
        
        return $this->render("TorgovorotTorgBundle:Admin:item_addr_profile.html.twig", array(
                "form" => $form->createView()
            ));
    }
    
    public function addCarAdsAction($id = 0, Request $request = null)
    {
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        
        
        $label = new AdsCars();
        
        if($id != 0 and $id > 0)
        {
            $label = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:AdsCars")->find($id);
        }
        
        $em = $this->getDoctrine()->getManager();
        
        $label_arr = array();
        
        $checks_arr = array();
        
        $labels = $em->getRepository("TorgovorotTorgBundle:Labels")->findAll();
        
        foreach ($labels as $label_one)
        {
            $label_arr[$label_one->getName()] = $em->createQuery("select c from TorgovorotTorgBundle:Characteristics as c JOIN TorgovorotTorgBundle:CharacterBind as b WITH b.characterId = c.id where b.adsType = 7 and b.labelId = ".$label_one->getId())
                 ->getResult();
        }
        
        $form = $this->createFormBuilder($label);
        $form->setAttribute("", "");
        $form->add('title','text',array(
                "label" => "Тип авто"
            ))
            //->add('catId')
            ->add('markId', 'entity', array(
                'class' => 'TorgovorotTorgBundle:Cars',
                'property' => 'name',
                'label' => 'Марка',
                'data' => 1
            ))
            ->add('model', 'text', array(
                "label" => "Модель"
            ))
            ->add('yearProd', 'text', array(
                "label" => "Год выпуска"
            ))
            ->add('bodyId', 'entity', array(
                'class' => 'TorgovorotTorgBundle:BodyType',
                'property' => 'name',
                'label' => 'Тип кузова'
            ))
            ->add('runDistance', 'text', array(
                "label" => "Пробег"
            ))
            ->add('yearsOld', 'choice', array(
                'label' => 'Срок владения',
                'choices' => array('0' => 'Меньше года', '1' => 'Больше года'),
                'required'    => false,
                'expanded' => true,
                'multiple' => false,
                'empty_value' => false
            ))
            ->add('wincode', 'text', array(
                "label" => "Винкод"
            ))
            ->add('volume', 'text', array(
                "label" => "Объем"
            ))
            ->add('power', 'text', array(
                "label" => "Мощность"
            ))
            ->add('engineId', 'entity', array(
                'class' => 'TorgovorotTorgBundle:EngineType',
                'property' => 'name',
                'label' => 'Топливо'
            ))
            ->add('conditionId', 'entity', array(
                'class' => 'TorgovorotTorgBundle:BodyCondition',
                'property' => 'name',
                'label' => 'Состояние'
            ))
            ->add('color', 'text', array(
                "label" => "Цвет"
            ))
            ->add('photo','file', array(
                "label" => "Фото",
                "mapped" => false,
                'required'    => false,
                "attr" => array(
                    "accept" => "image/*",
                    "multiple" => "multiple"
                )
            ))
            ->add('price', 'text', array(
                "label" => "Цена"
            ))
            ->add('torg', 'checkbox', array(
                "label" => "торг",
                "data" => $label->getTorg() == 1 ? true : false,
                "value" => 1
            ))
            ->add('isYourTown', 'checkbox', array(
                "label" => "Наличие в городе продажи",
                "value" => 1,
                "data" => $label->getIsYourTown() == 1 ? true : false
            ))
            ->add('town',"text", array(
                "label" => "Город продажи"
            ))
            ->add('description', 'textarea',array(
                "label" => "Текст объявления",
                "required" => false
            ))
            ->add('shortDesc','textarea', array(
                "label" => "Краткий текст обьявления",
                "required" => false
            ))
            ->add('save','submit', array(
                "label" => "Сохранить"
            ));
        
        $form = $form->getForm();

        $form->handleRequest($request);
        
        if($form->isValid())
        {
            $data = $form->getData();
            
            $chs = $request->get("chid");
            
            foreach($data as $key => $val)
                $$key = $val;
            
            
            $ch_id = "";
            if($chs)
            {
                $ch_id = implode(";", $chs);
            }
            
            $data->setMarkId($data->getMarkId()->getId());
            $data->setBodyId($data->getBodyId()->getId());
            $data->setEngineId($data->getEngineId()->getId());
            $data->setConditionId($data->getConditionId()->getId());
            
            $files = $form->get("photo")->getData();
            
            $photos = "";
            
            foreach($files as $file)
            {
                if($file != null)
                    $photos .= $this->savePhoto($file, array("type"=>"photo")).";";
            }
            
            $data->setPhotoIds($photos);
                        
            $data->setVip(0)
                 ->setEmergency(0)
                 ->setPremium(0)
                 ->setTime(new \DateTime("now"))
                 ->setUpdated(new \DateTime("now"))
                 ->setSpecial(0)
                 ->setViews(0);
            
            $data->setChIds($ch_id);
            
            $this->getDoctrine()->getManager()->persist($data);
            $this->getDoctrine()->getManager()->flush();
        }

        $checked = $label->getChIds() != "" ? explode(";", $label->getChIds()) : array();
        
        return $this->render("TorgovorotTorgBundle:Admin:item_auto.html.twig", array(
                "form" => $form->createView(),
                "chs" => $label_arr,
                "checked" => $checked
            ));
    }
    
    /*
     * Admin delete functions
     */
    
    public function deleteAdsAction($ads_type, $id)
    {
        
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        
        $ads = "";
        $ch = null;
        $em = $this->getDoctrine()->getManager();
        switch ($ads_type) 
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
         
         if($ads)
         {
             $object = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:$ads")->find($id);
             
             if($object != null)
             {
                 if($ads_type == 1)
                 {
                     switch($object->getAdsType())
                     {
                         case 1:
                         case 2:
                             $ch = $em->getRepository("TorgovorotTorgBundle:ChApartment")->find($object->getChId());
                             break;
                         case 3:
                             $ch = $em->getRepository("TorgovorotTorgBundle:ChHouse")->find($object->getChId());
                             break;
                         case 4:
                             $ch = $em->getRepository("TorgovorotTorgBundle:ChComm")->find($object->getChId());
                             break;
                         case 5:
                             $ch = $em->getRepository("TorgovorotTorgBundle:ChGarage")->find($object->getChId());
                             break;
                     }
                     if($ch != null)
                     {
                         $em->remove($ch);
                         $em->flush();
                     }
                 }
                 
                 $em->remove($object);
                 $em->flush();
             }
         }
         
         return $this->redirect($this->getRequest()->headers->get('referer'));
    }
    
    public function deletePageAction($id)
    {
        
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        
        $em = $this->getDoctrine()->getManager();
        
        $object = $em->getRepository("TorgovorotTorgBundle:Pages")->find($id);
        
        if($object != null)
        {
            $em->remove($object);
            $em->flush();
        }
        
        return $this->redirect($this->getRequest()->headers->get('referer'));
    }
    
    public function deleteCatsAction($id)
    {
        
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        
        $em = $this->getDoctrine()->getManager();
        
        $object = $em->getRepository("TorgovorotTorgBundle:Cats")->find($id);
        
        if($object != null)
        {
            $connection = $em->getConnection();
            
            
            $statement = $connection->prepare("DELETE FROM cat_bind WHERE main_id = :id");
            $statement->bindValue('id', $id);
            $statement->execute();
            
            $statement = $connection->prepare("DELETE FROM cat_bind WHERE pod_id = :id");
            $statement->bindValue('id', $id);
            $statement->execute();
            
            $em->remove($object);
            $em->flush();
        }
        
        return $this->redirect($this->getRequest()->headers->get('referer'));
    }
    
    public function deleteBannerAction($id)
    {
        
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        
        $em = $this->getDoctrine()->getManager();
        
        $object = $em->getRepository("TorgovorotTorgBundle:Banners")->find($id);
        
        if($object != null)
        {
            $em->remove($object);
            $em->flush();
        }
        
        return $this->redirect($this->getRequest()->headers->get('referer'));
    }
    
    public function configAction(Request $request = null)
    {
        $us = $this->checkIfIsAdmin();
        if($us === true)
        {
            
        }
        else return $us;
        
        $em = $this->getDoctrine()->getManager();
        
        $configs = $em->getRepository("TorgovorotTorgBundle:AdminConfig")->findAll();
        
        $form = $this->createFormBuilder();
        
        $form->add('save', 'submit');
        
        $form = $form->getForm();

        $form->handleRequest($request);
        
        if($form->isValid())
        {
            //$top = $request->get('top');
            //$prem = $request->get('premium');
            //$vip = $request->get('vip');
            $params = $request->request->all();
            foreach($params as $key=>$value)
            {
                if(!is_array($value))
                {
                    $config_param = new AdminConfig();
                    $config_param = $em->getRepository("TorgovorotTorgBundle:AdminConfig")->findOneBy(array('param1'=>$key));
                    
                    if($config_param != null)
                    {
                        $config_param->setVal($value);
                        $em->flush();
                    }
                }
            }
        }
        
        return $this->render("TorgovorotTorgBundle:Admin:admin_config.html.twig", array(
                "form" => $form->createView(),
                "config" => $configs
            ));
    }
}
