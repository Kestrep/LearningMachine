<?php

namespace App\Controller;

use App\Entity\Card;
use App\Form\CardType;
use App\Repository\CardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/card")
 * @Security("is_granted('ROLE_USER')", message="Vous devez vous connecter pour pouvoir accéder au menu")
 */
class CardController extends AbstractController
{
    /**
     * @Route("/", name="card_index", methods={"GET"})
     */
    public function index(CardRepository $cardRepository): Response
    {
        return $this->render('card/index.html.twig', [
            'cards' => $cardRepository->findUserCards(),
        ]);
    }

    /**
     * @Route("/ajax", name="ajax_get", methods={"GET"})
     */
    public function ajax(CardRepository $cardRepository, EntityManagerInterface $em): Response
    {
        return $this->json($cardRepository->findUserCards(), 200, [], ['groups' => 'card:read']);
    }

    /**
     * @Route("/ajax/update", name="ajax_update", methods={"POST"})
     */
    public function update(CardRepository $cardRepository, EntityManagerInterface $em, Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        
        $cardStage = $data['stage'];

        $card = $cardRepository->find($data['id']);

        $currentStage = $card->getStage();
        if($data['stage'] === 'up') {
            $card->setStage($currentStage + 1);
            $message = "Une carte apprise !!!";
        } else if ($data['stage'] === 'down') {
            $card->setStage($currentStage - 1);
            $message = "On va faire descendre la pression !";
        }
        
        $em->persist($card);
        $em->flush();


        // dd($cardRepository->find(3));

        return $this->json($$message, 201, [], ['groups' => 'card:read']);
    }

    /**
     * @Route("/play", name="card_play", methods={"GET"})
     */
    public function play(CardRepository $cardRepository): Response
    {
        return $this->render('card/play.html.twig', [
            'cards' => $cardRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="card_new", methods={"GET","POST"})
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        // dd($request->getContent());
        $card = new Card();
        $form = $this->createForm(CardType::class, $card);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            $card->setCreatedAt(new \DateTime);
            $card->setStage(2);
            $em->persist($card);
            $em->flush();

            return $this->redirectToRoute('card_index');
        }

        return $this->render('card/new.html.twig', [
            'card' => $card,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/new/ajax", name="card_new_ajax", methods={"POST"})
     */
    public function newAJAX(Request $request, EntityManagerInterface $em): Response
    {
        $card = new Card();
        $form = $this->createForm(CardType::class, $card);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $card->setCreatedAt(new \DateTime);
            $card->setStage(2);
            $em->persist($card);
            $em->flush();

            dump($card);
            return $this->json("Alles Clar", 201);
        }

        return $this->render('card/new.html.twig', [
            'card' => $card,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="card_show", methods={"GET"})
     */
    public function show(Card $card): Response
    {
        return $this->render('card/show.html.twig', [
            'card' => $card,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="card_edit", methods={"GET","POST"})
     * @Security("user === card.getSubCategory().getCategory().getUser()", message="Cette annonce ne vous appartient pas")
     */
    public function edit(Request $request, Card $card): Response
    {
        $form = $this->createForm(CardType::class, $card);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('card_index');
        }

        return $this->render('card/edit.html.twig', [
            'card' => $card,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="card_delete", methods={"POST"})
     */
    public function delete(Request $request, Card $card): Response
    {
        if ($this->isCsrfTokenValid('delete'.$card->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($card);
            $entityManager->flush();
        }

        return $this->redirectToRoute('card_index');
    }
}
