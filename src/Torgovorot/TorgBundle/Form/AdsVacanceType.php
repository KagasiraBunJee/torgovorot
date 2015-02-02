<?php

namespace Torgovorot\TorgBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Doctrine\ORM\EntityManager;

class AdsVacanceType extends AbstractType
{
    private $em;
    
    private $mid;
    
    public function __construct(EntityManager $em1, $mid= 0)
    {
        $this->em = $em1;
        //$this->mid = $mid;
    }
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //Образование
            $education = array(
                        0 => 'высшее',
                        1 => 'неполное высшее',
                        2 => 'среднее специальное',
                        3 => 'среднее',
                        4 => 'начальное',
                        5 => 'не важно');
            
            $sex = array(
                        0 => 'не важно',
                        1 => 'мужской',
                        2 => 'женский'
                    );
            
            $timetable_arr = array();
            
            $all_time = $this->em->getRepository('TorgovorotTorgBundle:Timetable')->findAll();
            
            foreach($all_time as $time)
            {
               $timetable_arr[$time->getId()] = $time->getName();  
            }
            
            $cat_bind = $this->em->getRepository('TorgovorotTorgBundle:CatBind')->findBy(array('mainId' => 2));
        
            $cat_arr = array();
        
            foreach($cat_bind as $cat_b)
            {
                $cat = $this->em->getRepository('TorgovorotTorgBundle:Cats')->findOneBy(array('id' => $cat_b->getPodId()));
            
                $cat_arr[$cat->getId()] = $cat->getName();
            
            }
            
            $family = array(
                        0 => 'не важно',
                        1 => 'холост(а)',
                        2 => 'женат(замужем)'
                    );
        
            
        $builder
            //->add('ownerId')
            ->add('title','text',array(
                'label' => 'Название'
            ))
            ->add('vacancyIds', 'choice', array(
                    'choices' => $cat_arr,
                    'multiple' => true,
                    'empty_value' => false,
                    'expanded' => true,
                    'label' => 'Вакансии',
                    'required'    => false))
            ->add('timetableIds', 'choice', array(
                    'choices' => $timetable_arr,
                    'multiple' => true,
                    'empty_value' => false,
                    'expanded' => true,
                    'label' => 'График работы',
                    'required'    => false))
            ->add('moneyFrom','text',array(
                'label' => 'Зарплата от',
                'required' => false
            ))
            ->add('moneyTo','text',array(
                'label' => 'Зарплата до',
                'required' => false
            ))
            ->add('education','choice', array(
                    'choices' => $education,
                    'expanded' => true,
                    'empty_value' => false,
                    'label' => 'Образование',
                    'required'    => false))
            ->add('experience','text',array(
                'label' => 'Стаж работы',
                'required' => false
            ))
            ->add('sex','choice', array(
                    'choices' => $sex,
                    'expanded' => false,
                    'empty_value' => false,
                    'label' => 'Пол',
                    'required'    => false))
            ->add('family','choice', array(
                    'choices' => $family,
                    'empty_value' => false,
                    'label' => 'Семейное положение',
                    'required'    => false))
            ->add('requirement','textarea',array(
                    'label' => 'Требования',
                    'required' => false
            ))
            //add('createTime')
            //->add('days')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Torgovorot\TorgBundle\Entity\AdsVacance'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'torgovorot_torgbundle_adsvacance';
    }
}
