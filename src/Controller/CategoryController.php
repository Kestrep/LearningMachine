<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SubcategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/category")
 * @Security("is_granted('ROLE_USER')", message="Vous devez vous connecter pour pouvoir continuer")
 */
class CategoryController extends AbstractController
{

    /**
     * @Route("/getCategories/", name="get_categories", methods={"POST"})
     */
    public function getCategories(CategoryRepository $categoryRepository) {
        $categories = $categoryRepository->findAllFromCurrentUser();

        return $this->json($categories, 200, [], ['group' => 'Category:list']);
    }

    /**
     * @Route("/getSubcategories/", name="get_relative_subcategories", methods={"POST"})
     */
    public function getRelativeSubcategories(SubcategoryRepository $subcategoryRepository, NormalizerInterface $normalizer, Request $request ): Response
    {

        $categoryID = json_decode($request->getContent(), true);
        return $this->json($subcategoryRepository->findAllFromGivenCategoryFromCurrentUser($categoryID), 200, [], ['groups' => 'Subcategory:list']);
    }



    /**
     * @Route("/new", name="category_new", methods={"GET","POST"})
     * @Route("/{id}/edit", name="category_edit", methods={"GET","POST"})
     */
    public function new(Request $request, Category $category = null, EntityManagerInterface $em): Response
    {
        // Vérification du user et création de la category s'il n'y a pas de category
        if($category && $category->getUser() !== $this->getUser()) {
            return $this->redirectToRoute('card_index');
        } else if(!$category) {
            $category = new Category();
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Si nouvelle category
            if($category->getID() == null) {
                $category->setUser($this->getUser());
                $category->setCreatedAt(new \DateTime());
            }
            $category->setUpdatedAt(new \DateTime());

            $em->persist($category);
            $em->flush();

            if($request->isXmlHttpRequest()) {
                return $this->json([
                    'message' => 'La catégorie a bien été crée',
                    'exclamation' => 'Hey !',
                    'color' => 'orange',
                    'icon' => 'feather',
                ], 201);
            };
            return $this->redirectToRoute('card_index');
        }

        // Form rendering
        if($request->isXmlHttpRequest()) {
            return new Response(
                $this->renderView('category/_form.html.twig', [
                    'category' => $category,
                    'form' => $form->createView(),
                ])
            );
        };

        return $this->render('category/form.html.twig', [
            'edit' => $category->getID() !== null,
            'category' => $category,
            'form' => $form->createView(),
            'options' => [
                'pageTitle' => 'Modifier la page'
            ]
        ]);
    }

    /**
     * @Route("/{id}", name="category_delete", methods={"GET"})
     */
    public function delete(Request $request, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->query->get('token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('card_index');
    }
}
