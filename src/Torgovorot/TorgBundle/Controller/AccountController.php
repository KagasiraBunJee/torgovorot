<?php

namespace Torgovorot\TorgBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Torgovorot\TorgBundle\Form\Type\RegistrationType;
use Torgovorot\TorgBundle\Form\Model\Registration;
use Torgovorot\TorgBundle\Entity\Users;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Torgovorot\TorgBundle\Helper\Processing\ProcessItem;
use Torgovorot\TorgBundle\Helper\Uploader\Document;
//
use Torgovorot\TorgBundle\Entity\AdsResume;
use Torgovorot\TorgBundle\Entity\AdsGoods;
use Torgovorot\TorgBundle\Entity\AdsVacance;
use Torgovorot\TorgBundle\Entity\AdsEvents;
use Torgovorot\TorgBundle\Entity\AdsVitrina;
use Torgovorot\TorgBundle\Entity\Banners;
use Torgovorot\TorgBundle\Entity\AdsRealty;
use Torgovorot\TorgBundle\Entity\AdsCars;
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
//forms
use Torgovorot\TorgBundle\Form\UsersType;
use Torgovorot\TorgBundle\Form\AddressType;
use Torgovorot\TorgBundle\Form\CatsType;
use Torgovorot\TorgBundle\Form\AdsGoodsType;
use Torgovorot\TorgBundle\Form\AdsVacanceType;
use Torgovorot\TorgBundle\Form\AdsResumeType;
use Torgovorot\TorgBundle\Form\BannersType;
use Torgovorot\TorgBundle\Form\AdsEventsType;
//endforms



class AccountController extends Controller
{
    public function registerAction()
    {
        //$registration = new Registration();
        $form = $this->createFormBuilder()
            //->add('user', new UsersType())
            ->setAction($this->generateUrl('account_create'))
            ->add('email', 'email')
            ->add('password', 'password')
            ->add('save', 'submit')
            ->getForm();
        
        return $this->render(
            'TorgovorotTorgBundle:Account:register.html.twig',
             array('form' => $form->createView())
        );

    }
    
    public function gggAction()
    {


        $form = $this->createFormBuilder()
            ->add('task', 'text')
            ->add('dueDate', 'date')
            ->add('save', 'submit')
            ->getForm();
        
        return $this->render('TorgovorotTorgBundle:Account:register.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $form = $this->createFormBuilder()
            //->add('user', new UsersType())
            ->setAction($this->generateUrl('account_create'))
            ->add('email', 'email')
            ->add('password', 'password')
            ->add('save', 'submit')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $registration = $form->getData();
            $user_login = explode("@", $registration['email']);
            
            $user = new Users();
            
            $user->setAccessLvl(0);
            $user->setRegisterTime(new \DateTime());
            $user->setEmail($registration['email']);
            $user->setUserName($user_login[0]);
            $user->setPassNorm($registration['password']);
            $user->setSalt(md5(time()));
            $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
            $password = $encoder->encodePassword($user->getPassNorm(), $user->getSalt());
            $user->setPassHex($password);  
            
            $em->persist($user);
            $em->flush();

            return $this->redirect($this->generateUrl('torgovorot_torg_homepage'));
        }

        return $this->render(
            'TorgovorotTorgBundle:Account:register.html.twig',
            array('form' => $form->createView())
        );
    }
    
    public function loginAction()
    {
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
    
    public function profileAction(Request $request = null)
    {
        
        $user = new Users();
        
        $user = $this->get('security.context')->getToken()->getUser();
        
        if ( !$this->get('security.context')->isGranted('ROLE_USER')) { 
            return $this->redirect($this->generateUrl('login'));
        }
        
        $fio = $user->getFio();
        
        $about = $user->getAbout();
        
        $contacts = $user->getContacts();
        
        $company_name = $user->getCompanyName();
        
        //save method
        $em = $this->getDoctrine()->getEntityManager();
        
        //about form
        $form_about = $this->createFormBuilder()
            //->add('user', new UsersType())
            ->setAction($this->generateUrl('account_create'))
            ->add('fio', 'text')
            ->add('about', 'textarea', array(
                'required' => false
            ))
            ->add('contacts', 'textarea', array(
                'required' => false
            ))
            ->add('company', 'text')
            ->add('photo', 'file')
            ->add('save', 'submit')
            ->getForm();

        //pass form
        $form_pass = $this->createFormBuilder()
            //->add('user', new UsersType())
            ->setAction($this->generateUrl('account_create'))
            ->add('newpass', 'repeated', array('type' => 'password', 'invalid_message' => 'Новые пароли не совпадают',
            'first_options'  => array('label' => 'Новый пароль'),
            'second_options' => array('label' => 'Подтвердите новый пароль'),
                ))
            ->add('oldpass', 'password')
            ->add('save', 'submit')
            ->getForm();        
        
        $form_about->handleRequest($request);
        $form_pass->handleRequest($request);
        
        
        if ($form_about->isValid()) {
            $data = $form_about->getData();
            $file = $form_about->get("photo")->getData();
            
            
            $fio = $data['fio'];
            $about = $data['about'];
            $contacts = $data['contacts'];
            
            $user->setAbout($about);
            $user->setFio($fio);
            $user->setContacts($contacts);
            $user->setCompanyName($data['company']);
            if(isset($data['photo']))
            {
                
                $pid = $this->savePhoto($data['photo'], array("type"=>"photo"));
                if($pid != "")
                    $user->setPhoto($pid);
            }
            $em->persist($user);
            $em->flush();            
        }

        if ($form_pass->isValid()) {
            $data = $form_pass->getData();
            
            $new_pass = $data['newpass'];
            
            $old_pass = $data['oldpass'];
            
            if($new_pass and $old_pass)
            {
                
                if($old_pass == $user->getPassNorm())
                {
                    $user->setPassNorm($new_pass);
                    $user->setSalt(md5(time()));
                    $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
                    $password = $encoder->encodePassword($user->getPassNorm(), $user->getSalt());
                    $user->setPassHex($password);

                    $em->persist($user);
                    $em->flush();                    
                } 
            }
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
        
        return $this->render(
            'TorgovorotTorgBundle:Default:profile.html.twig',
            array(
                'form_about' => $form_about->createView(), 
                'form_pass' => $form_pass->createView(),
                'fio' => $fio,
                'about' => $about,
                'contact' => $contacts,
                'company' => $company_name,
                'addrs' => $addrs,
                //'user' => $user,
                'vac' => $vac,
                'res' => $res,
                'goods' => $goods,
                'realty' => $realty,
                'rtype' => $r_arr
                )
        );
        
    }
    //address update
    
    //account items
    public function profileItemsAction($item_type =  1, Request $request = null)
    {
        
        $user = new Users();
        
        $user = $this->get('security.context')->getToken()->getUser();
        
        if ( !$this->get('security.context')->isGranted('ROLE_USER')) { 
            return $this->redirect($this->generateUrl('login'));
        }
        
        $ads_state = $request->get('state') != "" ? $request->get('state') : 1;
        
        //save method
        $em = $this->getDoctrine()->getEntityManager(); 
        
        $form = $this->createFormBuilder();
           $form->add('fio', 'text', array(
                    'data' => $user->getFio()
                ))
                ->add('mobile', 'text', array(
                    'data' => $user->getMobile()
                ))
                ->add('email', 'text', array(
                    'data' => $user->getEmail()
                ))
                ->add('pass', 'password', array(
                    "data" => $user->getPassNorm()
                ))
                ->add('companyName', 'text', array(
                    'data' => $user->getCompanyName()
                ))
                ->add('about', 'textarea', array(
                    'required' => false,
                    'data' => $user->getAbout()
                ))
                ->add('photo', 'file', array(
                    'required' => false
                ))
                ->add('save', 'submit')
                //->getForm()
                ;
        
        $form = $form->getForm();   
           
        $form->handleRequest($request);
        
        if($form->isValid())
        {
            $data = $form->getData();
            
            foreach($data as $key=>$value)
                $$key = $value;
            
            $user->setAbout($about);
            $user->setCompanyName($companyName);
            $user->setEmail($email);
            $user->setFio($fio);
            $user->setMobile($mobile);
            if(isset($pass) and $pass != "")
            {
                //echo $pass;
                $user->setPassNorm($pass);
                $user->setPassHex($this->encodePassword($pass, $user));
            }
            if(isset($photo))
            {
                
                $pid = $this->savePhoto($photo, array("type"=>"photo"));
                if($pid != "")
                    $user->setPhoto($pid);
            }
            $em->persist($user);
            $em->flush();
        }
        
        $addrs = "";
        $vac = "";
        $res = "";
        $goods = "";
        $realty = "";
        $r_arr = array();
        $items = array();
        $active_items = array();
        $canceled_items = array();
        $operating_items = array();
        switch($item_type)
        {
            case 1:
                $items = $this->getRealtiesByUserId($user->getId(),$ads_state);
                $active_items = $this->getRealtiesByUserId($user->getId(),1);
                $canceled_items = $this->getRealtiesByUserId($user->getId(),2);
                $operating_items = $this->getRealtiesByUserId($user->getId(),0);
                $realty_type = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:RealtyType')->findAll();
                
                foreach ($realty_type as $r)
                {
                    $r_arr[$r->getId()] = $r->getName();
                }
                break;
            case 2:
                $items = $this->getVacanciesByUserId($user->getId(),$ads_state);
                $active_items = $this->getVacanciesByUserId($user->getId(),1);
                $canceled_items = $this->getVacanciesByUserId($user->getId(),2);
                $operating_items = $this->getVacanciesByUserId($user->getId(),0);
                break;
            case 3:
                $items = $this->getResumesByUserId($user->getId(),$ads_state);
                $active_items = $this->getResumesByUserId($user->getId(),1);
                $canceled_items = $this->getResumesByUserId($user->getId(),2);
                $operating_items = $this->getResumesByUserId($user->getId(),0);
                break;
            case 4:
                $items = $this->getGoodsByUserId($user->getId(),$ads_state);
                $active_items = $this->getGoodsByUserId($user->getId(),1);
                $canceled_items = $this->getGoodsByUserId($user->getId(),2);
                $operating_items = $this->getGoodsByUserId($user->getId(),0);
                break;
            case 5:
                $items = $this->getEventsByUserId($user->getId(),$ads_state);
                $active_items = $this->getEventsByUserId($user->getId(),1);
                $canceled_items = $this->getEventsByUserId($user->getId(),2);
                $operating_items = $this->getEventsByUserId($user->getId(),0);
                break;
            case 8:
                $items = $this->getCarsByUserId($user->getId(),$ads_state);
                $active_items = $this->getCarsByUserId($user->getId(),1);
                $canceled_items = $this->getCarsByUserId($user->getId(),2);
                $operating_items = $this->getCarsByUserId($user->getId(),0);
                break;
            default:
                $items = $this->getAddressesByUserId($user->getAddrId());
                $active_items = $this->getAddressesByUserId($user->getId(),1);
                $canceled_items = $this->getAddressesByUserId($user->getId(),2);
                $operating_items = $this->getAddressesByUserId($user->getId(),0);
                break;
        }
        
        return $this->render(
            'TorgovorotTorgBundle:Default:profile_items.html.twig',
            array(
                //'user' => $user,
                'items' => $items,
                'canceled_items' => $canceled_items,
                'operating_items' => $operating_items,
                'active_items' => $active_items,
                'rtype' => $r_arr,
                'item_type' => $item_type,
                'form' => $form->createView(),
                'state' => $ads_state
                )
        );
    }
    
    public function profileAddrAction($id1, Request $request)
    {
        
        $user = new Users();
        
        $user = $this->get('security.context')->getToken()->getUser();
        
        $addr = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:Address")->findOneBy(array('id' => $id1, 'ownerId' => $user->getId()));
        
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
        
        return $this->render('TorgovorotTorgBundle:Default:item_addr_profile.html.twig', array(
                'form' => $form->createView(),
                'user' => $user
            ));
    }
    
    //realtyupdate
    public function updateRealtyAction($param, $id1, Request $request = null)
    {
                
        $form = $this->createFormBuilder()
                     ->setAction($this->generateUrl('profile_realty_view',array('param' => $param, 'id1' => $id1)));
        $em = $this->getDoctrine()->getEntityManager();
        
        $user = $this->get('security.context')->getToken()->getUser();
        
        
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
        
        $ads = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsRealty')->findOneBy(array('id' => $id1, 'ownerId' => $user->getId()));
        
        if($ads == null || $user->getId() != $ads->getOwnerId())
        {
            return $this->redirect($this->generateUrl("profile"));
        }
        
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
                    'data' => $ch->getVideoId(),
                    'required' => false
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
        return $this->render('TorgovorotTorgBundle:Default:realty_add.html.twig', array(
                'form' => $get_form->createView() , 
                'form_type' => $param, 
                'ads_type' => 'realty',
                'item' => $ads
           ));
    }
    
    //res update
    public function updateResAction($id1, Request $request)
    {
        
        $user = new Users();
        
        $em = $this->getDoctrine()->getEntityManager();
        
        
        
        $user = $this->get('security.context')->getToken()->getUser();
        
        $object = new AdsResume();
        
        $object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsResume')->findOneBy(array('id' => $id1, 'ownerId' => $user->getId()));
        
        if($object == null || $user->getId() != $object->getOwnerId())
        {
            return $this->redirect($this->generateUrl("profile"));
        }
        
        $object->setTimetable(explode(";",$object->getTimetable()));
        $object->setJobsIds(explode(";",$object->getJobsIds()));
        //print_r($object);
        
        $form = $this->createForm(new AdsResumeType($em),$object);
        
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
        
        return $this->render('TorgovorotTorgBundle:Default:ads_add.html.twig', array(
                'form' => $form->createView(),
                'user' => $user,
                'form_type' => '3',
                'cat' => '$jobs',
                'time' => '$time',
                'item' => $object
            ));
    }
    //vac update
    public function updateVacAction($id1, Request $request)
    {
        
        $user = new Users();
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $user = $this->get('security.context')->getToken()->getUser();
        
        $object = new AdsVacance();
        
        $object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsVacance')->findOneBy(array('id' => $id1, 'ownerId' => $user->getId()));
        
        if($object == null || $user->getId() != $object->getOwnerId())
        {
            return $this->redirect($this->generateUrl("profile"));
        }
        
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
        
        return $this->render('TorgovorotTorgBundle:Default:ads_add.html.twig', array(
                'form' => $form->createView(),
                'user' => $user,
                'form_type' => '2',
                'cat' => '$jobs',
                'time' => '$time',
                'item' => $object
            ));
    }
    //good update
    public function updateGoodAction($id1, Request $request)
    {
        
        $user = new Users();
        
        $user = $this->get('security.context')->getToken()->getUser();
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $object = new AdsGoods();
        
        $object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsGoods')->findOneBy(array('id' => $id1, 'ownerId' => $user->getId()));
        
        if($object == null || $user->getId() != $object->getOwnerId())
        {
            return $this->redirect($this->generateUrl("profile"));
        }
        
        $form = $this->createForm(new AdsGoodsType($em),$object);
        
        
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
        
        return $this->render('TorgovorotTorgBundle:Default:ads_add.html.twig', array(
                'form' => $form->createView(),
                'user' => $user,
                'form_type' => '4',
                'cat' => '$jobs',
                'time' => '$time',
                'item' => $object
            ));
    }
    
    //event update
    public function updateEventAction($id, Request $request)
    {
        
        $user = new Users();
        
        $user = $this->get('security.context')->getToken()->getUser();
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $object = new AdsEvents();
        
        $object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsEvents')->findOneBy(array('id' => $id, 'ownerId' => $user->getId()));
        
        $form = $this->createForm(new AdsEventsType($em),$object);
        
        if($object == null || $user->getId() != $object->getOwnerId())
        {
            return $this->redirect($this->generateUrl("profile"));
        }
        
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
        
        return $this->render('TorgovorotTorgBundle:Default:ads_add.html.twig', array(
                'form' => $form->createView(),
                'user' => $user,
                'form_type' => '5',
                'cat' => '$jobs',
                'time' => '$time',
                'item' => $object
            ));
    }
    
    //event cars
    public function updateCarAction($id, Request $request)
    {
        
        $label = new AdsCars();
        
        $user = new Users();
        
        $user = $this->get('security.context')->getToken()->getUser();
        
        if($id != 0 and $id > 0)
        {
            $label = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:AdsCars")->findOneBy(array("ownerId" => $user->getId(), "id" => $id));
        }
        
        if($label == null || $user->getId() != $label->getOwnerId())
        {
            return $this->redirect($this->generateUrl("profile"));
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
        //-----------------------Cars entity-------------------------------
        $cars = $em->getRepository("TorgovorotTorgBundle:Cars")->findAll();
        
        $cars_ent = array();
        
        foreach($cars as $car_em)
        {
            $cars_ent[$car_em->getId()] = $car_em->getName();
        }
        //------------------------------------------------------------------
        
        //-----------------------Body type entity-------------------------------
        $body = $em->getRepository("TorgovorotTorgBundle:BodyType")->findAll();
        
        $body_ent = array();
        
        foreach($body as $body_em)
        {
            $body_ent[$body_em->getId()] = $body_em->getName();
        }
        //------------------------------------------------------------------
        
        //-----------------------Engine type entity-------------------------------
        $engines = $em->getRepository("TorgovorotTorgBundle:EngineType")->findAll();
        
        $engines_ent = array();
        
        foreach($engines as $engine_em)
        {
            $engines_ent[$engine_em->getId()] = $engine_em->getName();
        }
        //------------------------------------------------------------------
        
        //-----------------------Condition entity-------------------------------
        $conditions = $em->getRepository("TorgovorotTorgBundle:BodyCondition")->findAll();
        
        $cond_ent = array();
        
        foreach($conditions as $cond_em)
        {
            $cond_ent[$cond_em->getId()] = $cond_em->getName();
        }
        //------------------------------------------------------------------
        
        /*echo "<pre>";
        print_r($car);
        echo "</pre>";*/
        $form = $this->createFormBuilder()
                
        ->add('title','text',array(
                "label" => "Тип авто",
                "data" => $label->getTitle()
            ))
            //->add('catId')
            ->add('markId', 'choice', array(
                'choices' => $cars_ent,
                'label' => 'Марка',
                'data' => $label->getMarkId()
                
            ))
            ->add('model', 'text', array(
                "label" => "Модель",
                "data" => $label->getModel()
            ))
            ->add('yearProd', 'text', array(
                "label" => "Год выпуска",
                "data" => $label->getYearProd()
            ))
            ->add('bodyId', 'choice', array(
                'choices' => $body_ent,
                'label' => 'Тип кузова',
                'data' => $label->getBodyId()
            ))
            ->add('runDistance', 'text', array(
                "label" => "Пробег",
                "data" => $label->getRunDistance()
            ))
            ->add('yearsOld', 'choice', array(
                'label' => 'Срок владения',
                'choices' => array('0' => 'Меньше года', '1' => 'Больше года'),
                'required'    => false,
                'expanded' => true,
                'multiple' => false,
                'empty_value' => false,
                'data' => $label->getYearsOld()
            ))
            ->add('wincode', 'text', array(
                "label" => "Винкод",
                "data" => $label->getWincode()
            ))
            ->add('volume', 'text', array(
                "label" => "Объем",
                "data" => $label->getVolume()
            ))
            ->add('power', 'text', array(
                "label" => "Мощность",
                "data" => $label->getPower()
            ))
            ->add('engineId', 'choice', array(
                'choices' => $engines_ent,
                'label' => 'Топливо',
                'data' => $label->getEngineId()
            ))
            ->add('conditionId', 'choice', array(
                'choices' => $cond_ent,
                'label' => 'Состояние',
                'data' => $label->getConditionId()
            ))
            ->add('color', 'text', array(
                "label" => "Цвет",
                "data" => $label->getColor()
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
                "label" => "Цена",
                "data" => $label->getPrice()
            ))
            ->add('torg', 'checkbox', array(
                "label" => "торг",
                "data" => $label->getTorg() == 1 ? true : false,
                
            ))
            ->add('isYourTown', 'checkbox', array(
                "label" => "Наличие в городе продажи",
                "value" => 1,
                "data" => $label->getIsYourTown() == 1 ? true : false
            ))
            ->add('town',"text", array(
                "label" => "Город продажи",
                "data" => $label->getTown()
            ))
            ->add('description', 'textarea',array(
                "label" => "Текст объявления",
                "required" => false,
                "data" => $label->getDescription()
            ))
            ->add('shortDesc','textarea', array(
                "label" => "Краткий текст обьявления",
                "required" => false,
                "data" => $label->getShortDesc()
            ))
            ->add('car_id', 'hidden',array(
                'data' => $label->getId()
            ))
            ->add('save','submit', array(
                "label" => "Сохранить",
                
            ));
        
        
        
        //$form->get("markId")->setData($car);
        
        $form = $form->getForm();

        $form->handleRequest($request);
        
        if($form->isValid())
        {
            $data = $form->getData();
            
            $car = new AdsCars();
            
            
            
            $chs = $request->get("chid");
            
            foreach($data as $key => $val)
                $$key = $val;
            
            
            
            $car = $em->getRepository("TorgovorotTorgBundle:AdsCars")->find($car_id);
            
            $ch_id = "";
            if($chs)
            {
                $ch_id = implode(";", $chs);
            }
            /*echo "<pre>";
            print_r($car);
            echo "</pre>";*/
            $car->setMarkId($markId);
            $car->setBodyId($bodyId);
            $car->setEngineId($engineId);
            $car->setConditionId($conditionId);
            
            $car->setTitle($title);
            $car->setColor($color);
            $car->setDescription($description);
            $car->setIsYourTown($isYourTown);
            $car->setModel($model);
            $car->setPower($power);
            $car->setPrice($price);
            $car->setRunDistance($runDistance);
            $car->setShortDesc($shortDesc);
            $car->setTorg($torg);
            $car->setTown($town);
            $car->setVolume($volume);
            $car->setWincode($wincode);
            $car->setYearProd($yearProd);
            $car->setYearsOld($yearsOld);
            
            $files = $form->get("photo")->getData();
            
            $photos = $label->getPhotoIds();
            
            foreach($files as $file)
            {
                if($file != null)
                    $photos .= $this->savePhoto($file, array("type"=>"photo")).";";
            }
            
            $car->setPhotoIds($photos);
                        
            $car->setVip(0)
                 ->setEmergency(0)
                 ->setPremium(0)
                 ->setTime(new \DateTime("now"))
                 ->setUpdated(new \DateTime("now"))
                 ->setSpecial(0)
                 ->setViews(0);
            
            $car->setChIds($ch_id);
            
            $this->getDoctrine()->getManager()->persist($car);
            $this->getDoctrine()->getManager()->flush();
        }

        $checked = $label->getChIds() != "" ? explode(";", $label->getChIds()) : array();
        
        return $this->render('TorgovorotTorgBundle:Default:ads_add.html.twig', array(
                'form' => $form->createView(),
                'user' => $user,
                'form_type' => '8',
                'cat' => '$jobs',
                'time' => '$time',
                "chs" => $label_arr,
                "checked" => $checked,
                'item' => $label
            ));
    }
    
    //add address
    
    public function addAdressAction(Request $request = null)
    {
        $user = new Users();
        
        $user = $this->get('security.context')->getToken()->getUser();
        
        $em = $this->getDoctrine()->getEntityManager();
        
        $form = $this->createForm(new AddressType($em));
        
        $form->add('save','submit', array(
            'label' => 'Сохранить'
        ));
        
        $form->handleRequest($request);
        
        if($form->isValid())
        {
            $data = new Address();
            
            $data = $form->getData();
            
            $data->setOwnerId($user->getId());
            
            $em->persist($data);
            $em->flush();
            
            $address = $user->getAddrId();
                        
            $address .= ";".$data->getId();
            
            $user = $em->getRepository('TorgovorotTorgBundle:Users')->find($user->getId());
            
            $user->setAddrId($address);
            
            $em->persist($user);
            $em->flush();
        }
        
        return $this->render('TorgovorotTorgBundle:Default:item_addr_profile.html.twig', array(
                'form' => $form->createView(),
                
            ));
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
    private function getVacanciesByUserId($id, $state)
    {
        $vacancies = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsVacance')->findBy(array('ownerId' => $id));
        
        if($vacancies)
        {
            return $vacancies;
        }
        else return array();
    }
    
    //resumes
    private function getResumesByUserId($id, $state)
    {
        $resumes = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsResume')->findBy(array('ownerId' => $id, 'adsState' => $state));
        
        if($resumes)
        {
            return $resumes;
        }
        else return array();
    }
    
    //goods
    private function getGoodsByUserId($id, $state)
    {
        $goods = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsGoods')->findBy(array('ownerId' => $id, 'adsState' => $state));
        
        if($goods)
        {
            return $goods;
        }
        else return array();
    }
    
    private function getEventsByUserId($id, $state)
    {
        $events = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsEvents')->findBy(array('ownerId' => $id, 'adsState' => $state));
        
        if($events)
        {
            return $events;
        }
        else return array();
    }
    
    private function getCarsByUserId($id, $state)
    {
        $cars = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsCars')->findBy(array('ownerId' => $id, 'adsState' => $state));
        
        if($cars)
        {
            return $cars;
        }
        else return array();
    }
    
    //realties
    private function getRealtiesByUserId($id, $state)
    {
        $object = $this->getDoctrine()->getRepository('TorgovorotTorgBundle:AdsRealty')->findBy(array('ownerId' => $id, 'adsState' => $state));
        
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
    
    public function deleteProfileItemAction($id = 0, $type = 0)
    {
        $user = new Users();
        
        $user = $this->get('security.context')->getToken()->getUser();
        
        $item = array();
        
        $em = $this->getDoctrine()->getManager();
        
        switch ($type)
        {
            //realty
            case 1:
                $item = $em->getRepository("TorgovorotTorgBundle:AdsRealty")->find($id);
                
                if($item->getOwnerId() == $user->getId())
                {
                    $ch_name = "";
                    switch($item->getAdsType())
                    {
                        case 1:
                        case 2:
                            $ch_name = "Apartment";
                            break;
                        case 3:
                            $ch_name = "House";
                            break;
                        case 4:
                            $ch_name = "Comm";
                            break;
                        case 5:
                            $ch_name = "Garage";
                            break;
                    }
                    $characterer = $em->getRepository("TorgovorotTorgBundle:Ch$ch_name")->find($item->getChId());
                    
                    $em->remove($characterer);
                    $em->flush();
                    
                    $em->remove($item);
                    $em->flush();
                }
                break;
            //vacanse
            case 2:
                $item = $em->getRepository("TorgovorotTorgBundle:AdsVacance")->find($id);
                if($item->getOwnerId() == $user->getId())
                {
                    $em->remove($item);
                    $em->flush();
                }
                break;
            case 3:
                $item = $em->getRepository("TorgovorotTorgBundle:AdsResume")->find($id);
                if($item->getOwnerId() == $user->getId())
                {
                    $em->remove($item);
                    $em->flush();
                }
                break;
            case 4:
                $item = $em->getRepository("TorgovorotTorgBundle:AdsGoods")->find($id);
                if($item->getOwnerId() == $user->getId())
                {
                    $em->remove($item);
                    $em->flush();
                }
                break;
            case 5:
                $item = $em->getRepository("TorgovorotTorgBundle:AdsEvents")->find($id);
                if($item->getOwnerId() == $user->getId())
                {
                    $em->remove($item);
                    $em->flush();
                }
                break;
            case 8:
                $item = $em->getRepository("TorgovorotTorgBundle:AdsCars")->find($id);
                if($item->getOwnerId() == $user->getId())
                {
                    $em->remove($item);
                    $em->flush();
                }
                break;
        }
        
        return $this->redirect($this->getRequest()->headers->get('referer'));
    }

    public function updateProfileItemAction($id = 0, $type = 0, $action = 0)
    {
        $user = new Users();
        
        $user = $this->get('security.context')->getToken()->getUser();
        
        $item = array();
        
        $em = $this->getDoctrine()->getManager();
        
        switch ($type)
        {
            //realty
            case 1:
                $item = $em->getRepository("TorgovorotTorgBundle:AdsRealty")->find($id);
                if($action == 2)
                {
                    $item->setAdsState($action);
                }
                elseif($action == 0)
                {
                    $item->setAdsState($action);
                }
                $em->flush();
                /*if($item->getOwnerId() == $user->getId())
                {
                    $ch_name = "";
                    switch($item->getAdsType())
                    {
                        case 1:
                        case 2:
                            $ch_name = "Apartment";
                            break;
                        case 3:
                            $ch_name = "House";
                            break;
                        case 4:
                            $ch_name = "Comm";
                            break;
                        case 5:
                            $ch_name = "Garage";
                            break;
                    }
                    $characterer = $em->getRepository("TorgovorotTorgBundle:Ch$ch_name")->find($item->getChId());
                    
                    $em->remove($characterer);
                    $em->flush();
                    
                    $em->remove($item);
                    $em->flush();
                }*/
                break;
            //vacanse
            case 2:
                $item = $em->getRepository("TorgovorotTorgBundle:AdsVacance")->find($id);
                if($action == 2)
                {
                    $item->setAdsState($action);
                }
                elseif($action == 1)
                {
                    $item->setAdsState($action);
                }
                $em->flush();
                break;
            case 3:
                $item = $em->getRepository("TorgovorotTorgBundle:AdsResume")->find($id);
                if($action == 2)
                {
                    $item->setAdsState($action);
                }
                elseif($action == 1)
                {
                    $item->setAdsState($action);
                }
                $em->flush();
                break;
            case 4:
                $item = $em->getRepository("TorgovorotTorgBundle:AdsGoods")->find($id);
                if($action == 2)
                {
                    $item->setAdsState($action);
                }
                elseif($action == 1)
                {
                    $item->setAdsState($action);
                }
                $em->flush();
                break;
            case 5:
                $item = $em->getRepository("TorgovorotTorgBundle:AdsEvents")->find($id);
                if($action == 2)
                {
                    $item->setAdsState($action);
                }
                elseif($action == 1)
                {
                    $item->setAdsState($action);
                }
                $em->flush();
                break;
            case 8:
                $item = $em->getRepository("TorgovorotTorgBundle:AdsCars")->find($id);
                if($action == 2)
                {
                    $item->setAdsState($action);
                }
                elseif($action == 1)
                {
                    $item->setAdsState($action);
                }
                $em->flush();
                break;
        }
        
        return $this->redirect($this->getRequest()->headers->get('referer'));
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
    
    public function liftUpAction($type, $id, Request $request)
    {
        $user = new Users();
        $user = $this->get('security.context')->getToken()->getUser();
        $repository = "";
        $action = "lift";
        if($user != null)
        {
            
            $routing = "";
            switch ($type)
            {
                case 1:
                    $routing = "object";
                    $repository = "AdsRealty";
                    break;
                case 2:
                    $routing = "vacance";
                    $repository = "AdsVacance";
                    break;
                case 3:
                    $routing = "resume";
                    $repository = "AdsResume";
                    break;
                case 4:
                    $routing = "good";
                    $repository = "AdsGoods";
                    break;
                case 5:
                    $routing = "event";
                    $repository = "AdsEvents";
                    break;
                default :
                    $routing = "";
                    break;
            }
            
            
            $form = $this->createFormBuilder()
            //->add('user', new UsersType())
            ->add('save', 'submit')
            ->add('id', 'hidden')
            ->getForm();
            
            $form->handleRequest($request);
        
            $data = $form->getData();
            
            $item = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:$repository")->find($id);
            
            if(isset($data['id']) and 100 < $user->getCredits())
            {
                
                $action = new ProcessItem($this->getDoctrine()->getEntityManager());
                $action->makeHigh($type, $id, $user);
                
                return $this->redirect($this->generateUrl($routing, array("id" => $id)));
                
            }
            
            return $this->render(
                'TorgovorotTorgBundle:Account:premium_page.html.twig',
                    array(
                        'form' => $form->createView(),
                        "message" => "поднять товар",
                        'price' => 100,
                        'user' => $user,
                        'item' => $item,
                        'action' => $action
                    )
            );
        }
    }
    
    public function makePremiumAction($type, $id, Request $request)
    {
        $user = new Users();
        $user = $this->get('security.context')->getToken()->getUser();
        $repository = "";
        $action = "emergency";
        if($user != null)
        {
            
            $routing = "";
            switch ($type)
            {
                case 1:
                    $routing = "object";
                    $repository = "AdsRealty";
                    break;
                case 2:
                    $routing = "vacance";
                    $repository = "AdsVacance";
                    break;
                case 3:
                    $routing = "resume";
                    $repository = "AdsResume";
                    break;
                case 4:
                    $routing = "good";
                    $repository = "AdsGoods";
                    break;
                case 5:
                    $routing = "event";
                    $repository = "AdsEvents";
                    break;
                default :
                    $routing = "";
                    break;
            }
            
            
            $form = $this->createFormBuilder()
            //->add('user', new UsersType())
            ->add('save', 'submit')
            ->add('id', 'hidden')
            ->getForm();
            
            $form->handleRequest($request);
            
            $data = $form->getData();
            
            $item = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:$repository")->find($id);
            
            if(isset($data['id']) and 100 < $user->getCredits() and $item->getEmergency() != 1)
            {
                
                $action = new ProcessItem($this->getDoctrine()->getEntityManager());
                $action->makePremium($type, $id, $user);
                
                return $this->redirect($this->generateUrl($routing, array("id" => $id)));
                
            }
            return $this->render(
                'TorgovorotTorgBundle:Account:premium_page.html.twig',
                    array('form' => $form->createView(),
                        "message" => "сделать срочным",
                        'price' => 100,
                        'user' => $user,
                        'item' => $item,
                        'action' => $action)
            );
        }     
    }
    
    public function makeVipAction($type, $id, Request $request)
    {
        $user = new Users();
        $user = $this->get('security.context')->getToken()->getUser();
        $repository = "";
        $action = "vip";
        if($user != null)
        {
            
            $routing = "";
            switch ($type)
            {
                case 1:
                    $routing = "object";
                    $repository = "AdsRealty";
                    break;
                case 2:
                    $routing = "vacance";
                    $repository = "AdsVacance";
                    break;
                case 3:
                    $routing = "resume";
                    $repository = "AdsResume";
                    break;
                case 4:
                    $routing = "good";
                    $repository = "AdsGoods";
                    break;
                case 5:
                    $routing = "event";
                    $repository = "AdsEvents";
                    break;
                default :
                    $routing = "";
                    break;
            }
            
            
            $form = $this->createFormBuilder()
            //->add('user', new UsersType())
            ->add('save', 'submit')
            ->add('id', 'hidden')
            ->getForm();

            $form->handleRequest($request);
            
            $data = $form->getData();
            
            $item = $this->getDoctrine()->getRepository("TorgovorotTorgBundle:$repository")->find($id);
            
            if(isset($data['id']) and 100 < $user->getCredits() and $item->getVip() != 1)
            {
                
                $action = new ProcessItem($this->getDoctrine()->getEntityManager());
                $action->makeVip($type, $id, $user);
                
                return $this->redirect($this->generateUrl($routing, array("id" => $id)));
                
            }
            
            return $this->render(
                'TorgovorotTorgBundle:Account:premium_page.html.twig',
                    array('form' => $form->createView(),
                        "message" => "разместить в топ",
                        'price' => 100,
                        'user' => $user,
                        'item' => $item,
                        'action' => $action
                        )
            );
        }      
    }
   
    private function savePhoto($file, $param = array())
    {
        $photo = new Document($file, 0, $this->getDoctrine()->getEntityManager());
        
        return $photo->upload($param);
    }
    
    private function encodePassword($pass_norm, $user)
    {
        $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
        $password = $encoder->encodePassword($pass_norm, $user->getSalt());
        return $password;
    }
    /*
     * end of help info functions
     */
    
    
}

