<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SubCategoryRepository;
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
     * @Route("/", name="category_index", methods={"GET"})
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

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
    public function getRelativeSubcategories(SubCategoryRepository $subcategoryRepository, NormalizerInterface $normalizer, Request $request ): Response
    {

        $categoryID = json_decode($request->getContent(), true);
        return $this->json($subcategoryRepository->findAllFromGivenCategoryFromCurrentUser($categoryID), 200, [], ['groups' => 'subCategory:list']);
    }



    /**
     * @Route("/new", name="category_new", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setCreatedAt(new \DateTime());
            $category->setUpdatedAt(new \DateTime());
            $category->setUser($this->getUser());

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
            return $this->redirectToRoute('category_index');
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

        return $this->render('category/new.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_show", methods={"GET"})
     */
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="category_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Category $category, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_delete", methods={"POST"})
     */
    public function delete(Request $request, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('category_index');
    }
}
