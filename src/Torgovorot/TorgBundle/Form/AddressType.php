<?php

namespace Torgovorot\TorgBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AddressType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city','text',array(
                'label' => 'Город'
            ))
            ->add('indexN','text',array(
                'label' => 'Индекс',
                'required' => false
            ))
            ->add('street','text',array(
                'label' => 'Улица'
            ))
            ->add('house','text',array(
                'label' => 'Дом'
            ))
            ->add('office','text',array(
                'label' => 'Офис'
            ))
            ->add('phone','text',array(
                'label' => 'Номер телефона',
                'required' => false
            ))
            ->add('fax','text',array(
                'label' => 'Факс',
                'required' => false
            ))
            ->add('name','text',array(
                'label' => 'Название',
                'required' => false
            ))
            ->add('hints','textarea',array(
                'label' => 'Примечания',
                'required' => false
            ))
                
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Torgovorot\TorgBundle\Entity\Address'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'torgovorot_torgbundle_address';
    }
}
