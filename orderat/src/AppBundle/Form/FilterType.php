<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Doctrine\ORM\EntityRepository;

class FilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->setMethod('GET')
          ->add('restaurant', EntityType::class, array(
          'class' => 'AppBundle:Restaurant',
          'choice_label' => 'name',
          'required' => false,
          'placeholder' => 'Choose a restaurant'
          ))

          ->add('myorders', CheckboxType::class, array(
              'label'    => 'My orders only',
              'required' => false,
          ))

          ->add('state', EntityType::class, array(
          'class' => 'AppBundle:State',
          'choice_label' => 'name',
          'required' => false,
          'placeholder' => 'Choose a state',

          'query_builder' => function (EntityRepository $er) {
          return $er->createQueryBuilder('s')
              ->orderBy('s.id', 'ASC');
          },

          ))

          ->add('save', ButtonType::class, array('label' => 'Filter'))
        ;
    }
}
