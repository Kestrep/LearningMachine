<?php

namespace App\Form;

use App\Entity\Card;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('created_at')
            ->add('front_maincontent', TextareaType::class)
            ->add('front_subcontent', TextareaType::class)
            ->add('back_main_content', TextareaType::class)
            ->add('back_subcontent', TextareaType::class)
            ->add('front_clue', TextareaType::class)
            ->add('back_clue', TextareaType::class)
            ->add('note', TextareaType::class)
            // ->add('stage')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Card::class,
        ]);
    }
}
