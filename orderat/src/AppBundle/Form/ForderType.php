<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ForderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('price', TextType::class)
          ->add('restaurant', EntityType::class, array(
          'class' => 'AppBundle:Restaurant',
          'choice_label' => 'name',
          ))
          ->add('save', SubmitType::class, array('label' => 'Start Order'))
        ;
    }
}
