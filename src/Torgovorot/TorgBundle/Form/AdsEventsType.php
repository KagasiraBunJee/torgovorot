<?php

namespace Torgovorot\TorgBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdsEventsType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title','text',array(
                'label' => 'Название'))
            ->add('shortDescr','textarea',array(
                'label' => 'Короткое описание',
                'required' => false
            ))
            ->add('description','textarea',array(
                'label' => 'Полное описание',
                'required' => false
            ))
            ->add('ageRestrict','text',array(
                'label' => 'Возрастное ограничение',
                'required' => false
            ))
            ->add('eventTime','date',array(
                'label' => 'Время начала',
                'required' => true))
            ->add('endEvent','date',array(
                'label' => 'Время окончания',
                'required' => true))
            ->add('photo','file', array(
                'mapped' => false,
                'required' => false
            ))
            ->add('else','textarea', array(
                'required' => false
            ))
            ->add('length','text',array(
                'label' => 'Длительность'))
            ->add('director','text',array(
                'label' => 'Режиссер'))
            ->add('genre','text',array(
                'label' => 'Жанр'))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Torgovorot\TorgBundle\Entity\AdsEvents'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'torgovorot_torgbundle_adsevents';
    }
}
