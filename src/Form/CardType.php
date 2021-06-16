<?php

namespace App\Form;

use App\Entity\Card;
use App\Entity\Category;
use App\Entity\SubCategory;
use App\Repository\CategoryRepository;
use App\Repository\SubCategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CardType extends AbstractType
{
    private $subCategoryRepository;
    private $categoryRepository;
    public function __construct(SubCategoryRepository $subCategoryRepository, CategoryRepository $categoryRepository) {
        $this->subCategoryRepository = $subCategoryRepository;
        $this->categoryRepository = $categoryRepository;

    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => Category::class,
            ])
            ->add('subCategory', EntityType::class, [
                'class' => SubCategory::class,
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
        $builder->get('category')->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) {
            $category = $event->getData();
            $form = $event->getForm()->getParent();

            $form->add('subCategory', EntityType::class, [
                'class' => SubCategory::class,
                'choices' => $this->subCategoryRepository->findAllFromGivenCategoryFromCurrentUser($category),
                'choice_label' => 'name'
            ]);

        });

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
            $form = $event->getForm();
            $card = $event->getData();
            $form->add('category', EntityType::class, [
                'class' => Category::class,
                // On veut les catégories par ordre alphabétique, mais que ce soit la dernière catégorie qui soit automatiquement sélectionnée
                'choices' => $this->categoryRepository->findAllFromCurrentUser(),
                'choice_label' => 'name',
                'mapped' => false,
                'data' => $card->getSubcategory() !== null ? $card->getSubcategory()->getCategory() : $this->categoryRepository->findLastCategoryFromUser()
            ]);
        });

        $builder->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event) {            
            $form = $event->getForm();

            $form->add('subCategory', EntityType::class, [
                'class' => SubCategory::class,
                'choice_label' => 'name',
                'choices' => $this->subCategoryRepository->findAllFromGivenCategoryFromCurrentUser($form->get('category')->getData()),
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Card::class,
            'csrf_protection' => false
        ]);
    }
}
