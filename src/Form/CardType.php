<?php

namespace App\Form;

use App\Entity\Card;
use App\Entity\Category;
use App\Entity\SubCategory;
use App\Repository\CategoryRepository;
use App\Repository\SubCategoryRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
                // 'data' => $this->categoryRepository->find(13)
            ])
            ->add('subCategory', EntityType::class, [
                'class' => SubCategory::class,
                'choices' => $this->subCategoryRepository->findAllFromCurrentUser(),
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

        $builder->get('subCategory')->addEventListener(FormEvents::PRE_SET_DATA, function(FormEvent $event) {
            $form = $event->getForm();
            $data = $form->getParent()->get('category')->getConfig()->getOption('data');

            $form->getParent()->add('subCategory', EntityType::class, [
                'class' => SubCategory::class,
                'choices' => $this->subCategoryRepository->findAllFromGivenCategoryFromCurrentUser($data),
                'data' => $this->subCategoryRepository->findLastSubCategoryFromGivenCategoryFromCurrentUser($data),
                'choice_label' => 'name'
            ]);

            // dd($data);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Card::class,
        ]);
    }
}
