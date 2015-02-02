<?php

namespace Torgovorot\TorgBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\Common\Collections\ArrayCollection;

use Doctrine\ORM\EntityManager;
class AdsResumeType extends AbstractType
{
    
    
    private $em;
    
    private $mid;
    
    public function __construct(EntityManager $em1, $mid = 0)
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
            
            $family = array(
                        0 => 'не важно',
                        1 => 'холост(а)',
                        2 => 'женат(замужем)'
                    );
            
            $driver = array(
                        0 => 'Нет',
                        1 => 'Да, имею права'
                    );
            
            $timetable_arr = array();
            
            $all_time = $this->em->getRepository('TorgovorotTorgBundle:Timetable')->findAll();
            
            foreach($all_time as $time)
            {
               $timetable_arr[$time->getId()] = $time->getName();  
            }
            
            $cat_bind = $this->em->getRepository('TorgovorotTorgBundle:CatBind')->findBy(array('mainId' => 3));
        
            $cat_arr = array();
        
            foreach($cat_bind as $cat_b)
            {
                $cat = $this->em->getRepository('TorgovorotTorgBundle:Cats')->findOneBy(array('id' => $cat_b->getPodId()));
            
                $cat_arr[$cat->getId()] = $cat->getName();
            
            }
            
            $children = array(
                        0 => 'Нет',
                        1 => 'Есть'
                    );
            
            $exped = array('0' => 'Нет опыта / студент', '1' => 'Есть опыт');
        $builder
            //->add('ownerId')
            ->add('fio','text',array(
                'label' => 'ФИО'
            ))
            ->add('position','text',array(
                'label' => 'Желаемая должность'
            ))
            ->add('birthDate','date',array(
                'label' => 'День рождение',
                'required' => false
            ))
            ->add('sex','choice',array(
                'choices' => $sex,
                'multiple' => false,
                'label' => 'Пол',
                'expanded' => true,
                'empty_value' => false,
                'required' => false))
            ->add('family','choice',array(
                'choices' => $family,
                'multiple' => false,
                'label' => 'Семейное положение',
                'expanded' => true,
                'empty_value' => false,
                'required' => false))
            ->add('driver','choice',array(
                'choices' => $driver,
                'multiple' => false,
                'empty_value' => false,
                'label' => 'Водительские права',
                'expanded' => true,
                'required' => false))
            ->add('children','choice',array(
                'choices' => $children,
                'multiple' => false,
                'expanded' => true,
                'empty_value' => false,
                'label' => 'Есть дети?',
                'required' => false
            ))
            ->add('jobsIds', 'choice', array(
                    'choices' => $cat_arr,
                    'multiple' => true,
                    'empty_value' => false,
                    'expanded' => true,
                    'label' => 'Професии',
                    'required'    => false))
            ->add('timetable','choice', array(
                    'choices' => $timetable_arr,
                    'multiple' => true,
                    'expanded' => true,
                    'label' => 'График работы',
                    'empty_value' => false,
                    'required'    => false))
            
            ->add('education','choice',array(
                'choices' => $education,
                'multiple' => false,
                'empty_value' => false,
                'label' => 'Образование',
                'expanded' => true,
                'required' => false))
            ->add('startStudy','date',array(
                'label' => 'Начало учебы',
                'required' => false
            ))
            ->add('endStudy','date',array(
                'label' => 'Окончание учебы',
                'required' => false
            ))
            //->add('isStudy')
            ->add('skills','textarea',array(
                'label' => 'Умения',
                'required' => false
            ))
            ->add('aboutMe','textarea',array(
                'label' => 'О себе',
                'required' => false
            ))
            ->add('contacts','textarea',array(
                'label' => 'Контакты',
                'required' => false
            ))
            //->add('createTime')
            ->add('experience','choice',array(
                'choices' => $exped,
                'multiple' => false,
                'label' => 'Опыт работы',
                'expanded' => true,
                'empty_value' => false,
                'required' => false))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Torgovorot\TorgBundle\Entity\AdsResume'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'torgovorot_torgbundle_adsresume';
    }
}
