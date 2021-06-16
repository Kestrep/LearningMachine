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
                'choices' => $this->categoryRepository->findAllFromCurrentUser(),
                'choice_label' => 'name',
                'mapped' => false,
                'data' => $this->categoryRepository->findLastCategoryFromUser()
            ])
            ->add('subCategory', EntityType::class, [
                'class' => SubCategory::class,
                'choice_label' => 'name'
            ])
            ->add('front_maincontent', TextareaType::class, ['required' => false])
            ->add('front_subcontent', TextareaType::class, ['required' => false])
            ->add('back_main_content', TextareaType::class, ['required' => false])
            ->add('back_subcontent', TextareaType::class, ['required' => false])
            ->add('front_clue', TextareaType::class, ['required' => false])
            ->add('back_clue', TextareaType::class, ['required' => false])
            ->add('note', TextareaType::class, ['required' => false])
        ;

        // Just after submitting a card, set the correct subcategory
        $builder->get('category')->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) {
            $category = $event->getData();
            $form = $event->getForm()->getParent();

            $form->add('subCategory', EntityType::class, [
                'class' => SubCategory::class,
                'choices' => $this->subCategoryRepository->findAllFromGivenCategoryFromCurrentUser($category),
                'choice_label' => 'name'
            ]);

        });

        $builder->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event) {
            // Si il y a une carte, on fait correspndre la sous-categorie, sinon on intiie les sous-categories relative à la categorie
            //$category = $event->getData()->getSubcategory()->getCategory();
            
            $form = $event->getForm();
            

            $form->add('subCategory', EntityType::class, [
                'class' => SubCategory::class,
                'choice_label' => 'name',
                'choices' => $this->subCategoryRepository->findAllFromGivenCategoryFromCurrentUser($form->get('category')->getData()),
            ]);
        });
        
        /*
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function(FormEvent $event) {
                $form = $event->getForm();
                $card = $event->getData();
                $category = [];
                $categoryFormData = $form->get('category')->getData();

                dump($categoryFormData, $card);

                $form->add('subCategory', EntityType::class, [
                    'class' => SubCategory::class,
                    'choices' => $this->subCategoryRepository->findAllFromGivenCategoryFromCurrentUser($categoryFormData),
                    'choice_label' => 'name'
                ]);
            }
        );

        $builder->get('subCategory')->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event) {
            $form = $event->getForm()->getParent();
            $data = $event->getForm()->getData(); // $form->getParent()->get('category')->getConfig()->getOption('data');

            dd($data);

            $form->add('subCategory', EntityType::class, [
                'class' => SubCategory::class,
                'choices' => $this->subCategoryRepository->findAllFromGivenCategoryFromCurrentUser($data),
                'choice_label' => 'name'
            ]);

        });
        */
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Card::class,
            'csrf_protection' => false
        ]);
    }
}
