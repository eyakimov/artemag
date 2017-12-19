<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProductType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('name', TextType::class)
                ->add('description', TextareaType::class, array(
                    'attr' => array('rows' => '2')
                ))
                ->add('price', MoneyType::class)
                ->add('amount', IntegerType::class)
                ->add('categories', EntityType::class, array(
                    'class' => Category::class,
                    'choice_label' => 'name',
                    'placeholder' => 'placeholder.optionChoose',
                    'multiple' => 'true',
                    'expanded' => 'true',
                ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }

}
