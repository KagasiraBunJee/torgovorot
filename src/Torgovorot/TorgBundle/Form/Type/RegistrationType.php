<?php

namespace Torgovorot\TorgBundle\Form\Type;

use Torgovorot\TorgBundle\Form\UsersType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('user', new UsersType());
        $builder->add('Зарегистрировать', 'submit');
    }

    public function getName()
    {
        return 'registration';
    }
}
