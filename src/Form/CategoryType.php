<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class CategoryType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', TextType::class)
                ->add('description', TextareaType::class, array(
                    'attr' => array('rows' => '2')
                ))
                ->add('parent', EntityType::class, array(
                    'class' => Category::class,
                    'choice_label' => 'name',
                    'placeholder' => 'placeholder.optionChoose',
                    'required' => false,
                    'empty_data' => null,
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }

}
