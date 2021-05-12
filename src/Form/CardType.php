<?php

namespace App\Form;

use App\Entity\Card;
use App\Entity\SubCategory;
use App\Repository\SubCategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CardType extends AbstractType
{
    private $subCategoryRepository;
    public function __construct(SubCategoryRepository $subCategoryRepository) {
        $this->subCategoryRepository = $subCategoryRepository;

    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subCategory', EntityType::class, [
                'class' => SubCategory::class,
                'choices' => $this->subCategoryRepository->findAllFromCurrentUser(),
                'choice_label' => 'name'
            ])
            ->add('front_maincontent', TextareaType::class)
            ->add('front_subcontent', TextareaType::class)
            ->add('back_main_content', TextareaType::class)
            ->add('back_subcontent', TextareaType::class)
            ->add('front_clue', TextareaType::class)
            ->add('back_clue', TextareaType::class)
            ->add('note', TextareaType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Card::class,
        ]);
    }
}
