<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\SubCategory;
use App\Form\SubCategoryType;
use App\Repository\SubCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/subcategory")
 */
class SubCategoryController extends AbstractController
{
    // TODO : find a way to merge new and edit
    /**
     * @Route("/new/{category}", name="sub_category_new", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface $em, Category $category = null): Response
    {
        $subCategory = new SubCategory();
        if($category) {
            if($category->getUser() !== $this->getUser()) {
                return $this->redirectToRoute('card_index');
            }
            $subCategory->setCategory($category);
        }
        $form = $this->createForm(SubCategoryType::class, $subCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subCategory->setCreatedAt(new \DateTime());
            $subCategory->setUpdatedAt(new \DateTime());

            // TODO Ajouter une categorie anonyme en cas de non sélection
            // $categories = $this->getUser()->getCategories();
            // $subCategory->setCategory($categories[0]);

            $em->persist($subCategory);
            $em->flush();

            if($request->isXmlHttpRequest()) {
                return $this->json([
                    'message' => 'La sous-catégorie a bien été crée',
                    'exclamation' => 'Hey !',
                    'color' => 'orange',
                    'icon' => 'feather',
                ], 201);
            };
            return $this->redirectToRoute('sub_category_index');
        }

        return $this->render('sub_category/_form.html.twig', [
            'sub_category' => $subCategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="sub_category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, SubCategory $subCategory, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SubCategoryType::class, $subCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('sub_category_index');
        }

        return $this->render('sub_category/form.html.twig', [
            'edit' => $subCategory !== null,
            'sub_category' => $subCategory,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/{id}", name="sub_category_delete", methods={"POST"})
     */
    public function delete(Request $request, SubCategory $subCategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subCategory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($subCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('sub_category_index');
    }
}
