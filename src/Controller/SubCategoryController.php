<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Subcategory;
use App\Form\SubcategoryType;
use App\Repository\SubcategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/subcategory")
 */
class SubcategoryController extends AbstractController
{
    // TODO : find a way to merge new and edit
    /**
     * @Route("/new/{category}", name="subcategory_new", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface $em, Category $category = null): Response
    {
        $subcategory = new Subcategory();
        if($category) {
            if($category->getUser() !== $this->getUser()) {
                return $this->redirectToRoute('card_index');
            }
            $subcategory->setCategory($category);
        }
        $form = $this->createForm(SubcategoryType::class, $subcategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $subcategory->setCreatedAt(new \DateTime());
            $subcategory->setUpdatedAt(new \DateTime());

            // TODO Ajouter une categorie anonyme en cas de non sélection
            // $categories = $this->getUser()->getCategories();
            // $Subcategory->setCategory($categories[0]);

            $em->persist($subcategory);
            $em->flush();

            if($request->isXmlHttpRequest()) {
                return $this->json([
                    'message' => 'La sous-catégorie a bien été crée',
                    'exclamation' => 'Hey !',
                    'color' => 'orange',
                    'icon' => 'feather',
                ], 201);
            };
            return $this->redirectToRoute('card_index');
        }

        return $this->render('subcategory/form.html.twig', [
            'edit' => false,
            'subcategory' => $subcategory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="subcategory_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Subcategory $subcategory, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(SubcategoryType::class, $subcategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            return $this->redirectToRoute('card_index');
        }

        return $this->render('subcategory/form.html.twig', [
            'edit' => $subcategory->getId() !== null,
            'subcategory' => $subcategory,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/{id}", name="subcategory_delete", methods={"GET"})
     */
    public function delete(Request $request, Subcategory $subcategory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$subcategory->getId(), $request->query->get('token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($subcategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('card_index');
    }
}
