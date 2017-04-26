<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RestaurantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('name', TextType::class)
          ->add('menuurl', TextType::class, array(
            'label' => 'Menu url',
            'required'    => false
          ))
          ->add('save', SubmitType::class, array('label' => 'Add Restaurant'))
        ;
    }
}
