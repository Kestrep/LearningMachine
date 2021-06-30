<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\SubCategory;
use App\Repository\CategoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubCategoryType extends AbstractType
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository) {
        $this->categoryRepository = $categoryRepository;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choices' => $this->categoryRepository->findAllFromCurrentUser()
            ])
            ->add('name')
            ->add('description')
            // ->add('updatedAt')
            // ->add('createdAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SubCategory::class,
        ]);
    }
}
